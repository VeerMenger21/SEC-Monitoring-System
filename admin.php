<?php
include "php/admin_check.php";
include "config.php";

// PHP ONLY fetches raw data — no calculations

// All users
$users = $conn->query("SELECT id, name, username, email, role, DATE(created_at) as joined_date FROM users WHERE is_deleted = 0 ORDER BY id DESC");

// Total cost per user (PHP fetches, that's it)
$cost_query = $conn->query("SELECT user_id, SUM(units_consumed * rate_per_unit) AS total_cost, SUM(units_consumed) AS total_units FROM energy_usage WHERE is_deleted = 0 GROUP BY user_id");
$user_costs = [];
while ($c = $cost_query->fetch_assoc()) {
    $user_costs[$c['user_id']] = $c;
}

// All energy usage with username
$usage = $conn->query("SELECT e.id, u.username, e.date, e.units_consumed, e.rate_per_unit, e.created_at FROM energy_usage e JOIN users u ON e.user_id = u.id WHERE e.is_deleted = 0 AND u.is_deleted = 0 ORDER BY e.date DESC LIMIT 50");

// All feedback with username
$fb = $conn->query("SELECT f.id, u.username, f.type, f.message, f.rating, f.created_at FROM feedback f JOIN users u ON f.user_id = u.id WHERE f.is_deleted = 0 AND u.is_deleted = 0 ORDER BY f.created_at DESC LIMIT 30");

// All contact messages
$contact_msgs = $conn->query("SELECT c.id, u.username, c.name, c.email, c.subject, c.message, c.created_at FROM contact_messages c JOIN users u ON c.user_id = u.id WHERE c.is_deleted = 0 AND u.is_deleted = 0 ORDER BY c.created_at DESC LIMIT 30");

// Count stats for cards
$user_count = $conn->query("SELECT COUNT(*) as c FROM users WHERE is_deleted = 0")->fetch_assoc()['c'];
$usage_count = $conn->query("SELECT COUNT(*) as c FROM energy_usage WHERE is_deleted = 0")->fetch_assoc()['c'];
$fb_count = $conn->query("SELECT COUNT(*) as c FROM feedback WHERE is_deleted = 0")->fetch_assoc()['c'];
$contact_count = $conn->query("SELECT COUNT(*) as c FROM contact_messages WHERE is_deleted = 0")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel — Smart Energy System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <h1>🔒 Admin Panel — Smart Energy System</h1>
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="dashboard.php">Dashboard</a>
    <a href="admin.php" class="active">Admin</a>
    <a href="reports.php">Reports</a>
    <a href="feedback.php">Feedback</a>
    <a href="contact.php">Contact</a>
    <a href="php/logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
    <a href="javascript:void(0)" onclick="toggleDarkMode()" class="dark-toggle" title="Toggle Dark Mode">🌙</a>
</nav>

<div class="main">

    <!-- ADMIN SUMMARY CARDS -->
    <div class="card-grid">
        <div class="card blue">
            <h3>Total Users</h3>
            <div class="value"><?php echo $user_count; ?></div>
        </div>
        <div class="card green">
            <h3>Usage Entries</h3>
            <div class="value"><?php echo $usage_count; ?></div>
        </div>
        <div class="card orange">
            <h3>Feedbacks</h3>
            <div class="value"><?php echo $fb_count; ?></div>
        </div>
        <div class="card red">
            <h3>Contact Msgs</h3>
            <div class="value"><?php echo $contact_count; ?></div>
        </div>
    </div>

    <!-- ALL USERS TABLE -->
    <div class="section-box">
        <h2>👤 All Users</h2>
        <table class="data-table">
            <thead>
                <tr><th>#</th><th>Name</th><th>Username</th><th>Email</th><th>Role</th><th>Total Usage</th><th>Total Cost (₹)</th><th>Joined</th><th>Action</th></tr>
            </thead>
            <tbody>
            <?php $sno1 = 1; while($row = $users->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $sno1++; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <?php 
                        $displayRole = $row['role'];
                        if ($row['username'] === 'veermenger') $displayRole = 'admin';
                    ?>
                    <td><?php echo $displayRole; ?></td>
                    <?php
                        $uCost = isset($user_costs[$row['id']]) ? $user_costs[$row['id']] : null;
                        $totalUnits = $uCost ? number_format($uCost['total_units'], 2) : '0.00';
                        $totalCost = $uCost ? number_format($uCost['total_cost'], 2) : '0.00';
                    ?>
                    <td><?php echo $totalUnits; ?> kWh</td>
                    <td>₹<?php echo $totalCost; ?></td>
                    <td><?php echo $row['joined_date']; ?></td>
                    <td>
                        <?php if($displayRole !== 'admin'): ?>
                        <a href="php/delete_user.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Delete user <?php echo htmlspecialchars($row['username']); ?> and all their data?')">Delete</a>
                        <?php else: ?>
                        <span style="color:#999;">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- ALL ENERGY USAGE -->
    <div class="section-box">
        <h2>📊 All Energy Usage (latest 50)</h2>
        <table class="data-table">
            <thead>
                <tr><th>#</th><th>User</th><th>Date</th><th>Units (kWh)</th><th>Rate (₹)</th><th>Est. Bill (₹)</th><th>Action</th></tr>
            </thead>
            <tbody>
            <?php $sno2 = 1; while($row = $usage->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $sno2++; ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo $row['units_consumed']; ?></td>
                    <td>₹<?php echo $row['rate_per_unit']; ?></td>
                    <td>₹<?php echo number_format($row['units_consumed'] * $row['rate_per_unit'], 2); ?></td>
                    <td>
                        <a href="php/delete_usage.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Delete this usage entry?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- ALL FEEDBACK -->
    <div class="section-box">
        <h2>💬 All Feedback (latest 30)</h2>
        <table class="data-table">
            <thead>
                <tr><th>User</th><th>Type</th><th>Message</th><th>Rating</th><th>Date</th></tr>
            </thead>
            <tbody>
            <?php while($row = $fb->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['type']); ?></td>
                    <td><?php echo htmlspecialchars($row['message']); ?></td>
                    <td><?php echo str_repeat('★', $row['rating']); ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- ALL CONTACT MESSAGES -->
    <div class="section-box">
        <h2>✉️ All Contact Messages (latest 30)</h2>
        <table class="data-table">
            <thead>
                <tr><th>User</th><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Date</th></tr>
            </thead>
            <tbody>
            <?php while($row = $contact_msgs->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['subject']); ?></td>
                    <td><?php echo htmlspecialchars($row['message']); ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

<footer>
    <p>&copy; 2026 Smart Energy Consumption Monitoring System | Created by Veer Menger</p>
</footer>

<script src="js/darkmode.js"></script>
</body>
</html>
