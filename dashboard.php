<?php
include "php/auth_check.php";
include "config.php";

$uid = $_SESSION['user_id'];
$username = $_SESSION['username'];
$user_name = $_SESSION['user_name'];

// PHP ONLY fetches raw data — NO calculations here

// Fetch energy usage rows
$stmt = $conn->prepare("SELECT id, date, units_consumed, rate_per_unit FROM energy_usage WHERE user_id = ? AND is_deleted = 0 ORDER BY date DESC");
$stmt->bind_param("i", $uid);
$stmt->execute();
$usage_result = $stmt->get_result();
$usage_data = [];
while ($row = $usage_result->fetch_assoc()) {
    $usage_data[] = $row;
}

// Fetch appliance usage rows
$stmt2 = $conn->prepare("SELECT appliance_name, wattage, hours_used, date FROM appliance_usage WHERE user_id = ? AND is_deleted = 0 ORDER BY date DESC");
$stmt2->bind_param("i", $uid);
$stmt2->execute();
$appliance_result = $stmt2->get_result();
$appliance_data = [];
while ($row = $appliance_result->fetch_assoc()) {
    $appliance_data[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Smart Energy System</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<header>
    <h1>⚡ Smart Energy Consumption Monitoring System</h1>
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="dashboard.php" class="active">Dashboard</a>
    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <a href="admin.php">Admin</a>
    <?php endif; ?>
    <a href="reports.php">Reports</a>
    <a href="feedback.php">Feedback</a>
    <a href="contact.php">Contact</a>
    <a href="php/logout.php">Logout (<?php echo htmlspecialchars($username); ?>)</a>
    <a href="javascript:void(0)" onclick="toggleDarkMode()" class="dark-toggle" title="Toggle Dark Mode">🌙</a>
</nav>

<div class="main">

    <!-- WELCOME -->
    <div class="section-box" style="text-align:center; padding:16px;">
        <h2 style="border:none; margin:0; padding:0;">Welcome, <?php echo htmlspecialchars($user_name); ?>! 👋</h2>
        <?php
        // ─── READING COOKIE: Display last login time ────────────
        // The "last_login" cookie was set during the PREVIOUS login.
        // We read it here using $_COOKIE to show the user when they
        // last visited the site.
        if (isset($_COOKIE['last_login'])) {
            echo '<p style="color:#888; margin:8px 0 0; font-size:14px;">🕐 Last login: ' . htmlspecialchars($_COOKIE['last_login']) . '</p>';
        }

        // ─── READING SESSION: Display current session login time ──
        if (isset($_SESSION['login_time'])) {
            echo '<p style="color:#888; margin:4px 0 0; font-size:13px;">📌 Current session started: ' . htmlspecialchars($_SESSION['login_time']) . '</p>';
        }
        ?>
    </div>

    <!-- ALERTS (JS will populate) -->
    <div id="alertArea"></div>

    <!-- SUMMARY CARDS -->
    <div class="card-grid" id="section-monitoring">
        <div class="card blue">
            <h3>Today's Usage</h3>
            <div class="value" id="todayUsage">0 kWh</div>
            <div class="sub" id="todayDate"></div>
        </div>
        <div class="card green">
            <h3>This Month</h3>
            <div class="value" id="monthUsage">0 kWh</div>
            <div class="sub" id="monthLabel"></div>
        </div>
        <div class="card orange">
            <h3>Estimated Bill</h3>
            <div class="value" id="estBill">₹0</div>
            <div class="sub">Based on current rate</div>
        </div>
        <div class="card red">
            <h3>vs Last Month</h3>
            <div class="value" id="changePercent">0%</div>
            <div class="sub" id="changeLabel">Change</div>
        </div>
    </div>

    <!-- GRID: FORMS + CHART -->
    <div class="grid-2" id="section-analytics">

        <!-- LEFT: INPUT FORMS -->
        <div>
            <!-- Energy Usage Form -->
            <div class="section-box">
                <h2>📊 Log Energy Usage</h2>
                <form action="php/save_usage.php" method="POST" onsubmit="return validateUsageForm()">
                    <label>Date</label>
                    <input type="date" name="date" id="usageDate" required>
                    <div class="error-msg" id="usageDateErr"></div>

                    <label>Units Consumed (kWh)</label>
                    <input type="number" step="0.01" name="units" id="usageUnits" placeholder="e.g. 12.5" required>
                    <div class="error-msg" id="usageUnitsErr"></div>

                    <label>Rate per Unit (₹)</label>
                    <input type="number" step="0.01" name="rate" id="usageRate" value="7.50" placeholder="e.g. 7.50" required>
                    <div class="error-msg" id="usageRateErr"></div>

                    <button type="submit" class="btn btn-primary">Save Usage</button>
                </form>
            </div>

            <!-- Appliance Usage Form -->
            <div class="section-box" id="section-appliance">
                <h2>🔌 Log Appliance Usage</h2>
                <form action="php/save_appliance.php" method="POST" onsubmit="return validateApplianceForm()">
                    <label>Appliance Name</label>
                    <input type="text" name="appliance_name" id="appName" placeholder="e.g. AC, Fridge, Heater" required>
                    <div class="error-msg" id="appNameErr"></div>

                    <label>Wattage (W)</label>
                    <input type="number" step="0.01" name="wattage" id="appWattage" placeholder="e.g. 1500" required>
                    <div class="error-msg" id="appWattageErr"></div>

                    <label>Hours Used</label>
                    <input type="number" step="0.5" name="hours" id="appHours" placeholder="e.g. 3" required>
                    <div class="error-msg" id="appHoursErr"></div>

                    <label>Date</label>
                    <input type="date" name="date" id="appDate" required>
                    <div class="error-msg" id="appDateErr"></div>

                    <button type="submit" class="btn btn-success">Save Appliance Usage</button>
                </form>
            </div>
        </div>

        <!-- RIGHT: CHARTS -->
        <div>
            <div class="chart-box">
                <h2>📈 Last 7 Days Usage</h2>
                <canvas id="barChart"></canvas>
            </div>

            <div class="chart-box">
                <h2>🍩 Appliance Breakdown</h2>
                <canvas id="pieChart"></canvas>
                <p id="noApplianceMsg" style="text-align:center; color:#999; display:none;">No appliance data yet</p>
            </div>
        </div>
    </div>

    <!-- ENERGY TIPS (JS-generated) -->
    <div class="section-box" id="tipsSection">
        <h2>💡 Energy Saving Tips</h2>
        <div id="tipsArea"></div>
    </div>

    <!-- RECENT USAGE TABLE -->
    <div class="section-box">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap;">
            <h2 style="border:none; margin:0; padding:0;">📋 Recent Usage History</h2>
            <div class="no-print" style="display:flex; gap:8px;">
                <a href="php/export_csv.php" class="btn btn-success" style="width:auto; margin:0; padding:8px 16px; font-size:13px;">📥 Export CSV</a>
                <a href="reports.php" class="btn btn-primary" style="width:auto; margin:0; padding:8px 16px; font-size:13px;">📋 Full Report</a>
            </div>
        </div>
        <table class="data-table">
            <thead>
                <tr><th>Date</th><th>Units (kWh)</th><th>Rate (₹)</th><th>Cost (₹)</th><th class="no-print">Action</th></tr>
            </thead>
            <tbody id="usageTableBody">
                <tr><td colspan="5" style="text-align:center; color:#999;">No data yet</td></tr>
            </tbody>
        </table>
    </div>

</div>

<footer>
    <p>&copy; 2026 Smart Energy Consumption Monitoring System | Created by Veer Menger</p>
</footer>

<!-- PHP passes RAW data to JS — JS does ALL calculations -->
<script>
    const usageData = <?php echo json_encode($usage_data); ?>;
    const applianceData = <?php echo json_encode($appliance_data); ?>;
</script>
<script src="js/dashboard.js"></script>
<script src="js/darkmode.js"></script>

</body>
</html>
