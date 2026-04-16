<?php
include "php/auth_check.php";
include "config.php";

$uid = $_SESSION['user_id'];
$username = $_SESSION['username'];

// PHP ONLY fetches raw data — JS does ALL calculations
$stmt = $conn->prepare("SELECT date, units_consumed, rate_per_unit FROM energy_usage WHERE user_id = ? ORDER BY date DESC");
$stmt->bind_param("i", $uid);
$stmt->execute();
$result = $stmt->get_result();
$usage_data = [];
while ($row = $result->fetch_assoc()) {
    $usage_data[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Report — Smart Energy System</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Print-specific styles */
        @media print {
            nav, footer, .no-print, .btn { display: none !important; }
            header { background: #0a3d62 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            body { background: white !important; }
            .card { box-shadow: none !important; border: 1px solid #ddd; }
            .section-box, .chart-box { box-shadow: none !important; border: 1px solid #ddd; }
        }
    </style>
</head>
<body>

<header>
    <h1>⚡ Smart Energy Consumption Monitoring System</h1>
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="dashboard.php">Dashboard</a>
    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <a href="admin.php">Admin</a>
    <?php endif; ?>
    <a href="reports.php" class="active">Reports</a>
    <a href="feedback.php">Feedback</a>
    <a href="php/logout.php">Logout (<?php echo htmlspecialchars($username); ?>)</a>
    <a href="javascript:void(0)" onclick="toggleDarkMode()" class="dark-toggle" title="Toggle Dark Mode">🌙</a>
</nav>

<div class="main">

    <div class="section-box" style="text-align:center; padding:16px;">
        <h2 style="border:none; margin:0; padding:0;">📋 Monthly Energy Report</h2>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="no-print" style="display:flex; gap:12px; margin-bottom:20px; justify-content:center;">
        <button class="btn btn-primary" style="width:auto;" onclick="window.print()">🖨️ Print Report</button>
        <a href="php/export_csv.php" class="btn btn-success" style="width:auto;">📥 Export CSV</a>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="card-grid" id="reportCards">
        <div class="card blue">
            <h3>Total Usage (All Time)</h3>
            <div class="value" id="totalAll">0 kWh</div>
        </div>
        <div class="card green">
            <h3>This Month</h3>
            <div class="value" id="thisMonth">0 kWh</div>
        </div>
        <div class="card orange">
            <h3>Estimated Bill</h3>
            <div class="value" id="billEst">₹0</div>
        </div>
        <div class="card red">
            <h3>vs Last Month</h3>
            <div class="value" id="monthChange">0%</div>
        </div>
    </div>

    <!-- MONTHLY CHART -->
    <div class="chart-box">
        <h2>📈 Monthly Usage Trend</h2>
        <canvas id="monthlyChart"></canvas>
    </div>

    <!-- MONTHLY BREAKDOWN TABLE -->
    <div class="section-box">
        <h2>📊 Month-by-Month Breakdown</h2>
        <table class="data-table">
            <thead>
                <tr><th>Month</th><th>Total kWh</th><th>Avg Rate (₹)</th><th>Estimated Bill (₹)</th></tr>
            </thead>
            <tbody id="monthlyTableBody">
                <tr><td colspan="4" style="text-align:center; color:#999;">No data</td></tr>
            </tbody>
        </table>
    </div>

</div>

<footer>
    <p>&copy; 2026 Smart Energy Consumption Monitoring System | Created by Veer Menger</p>
</footer>

<script>
    const usageData = <?php echo json_encode($usage_data); ?>;
</script>
<script src="js/reports.js"></script>
<script src="js/darkmode.js"></script>

</body>
</html>
