<?php
include "auth_check.php";
include "../config.php";

$uid = $_SESSION['user_id'];

// Fetch user's energy usage
$stmt = $conn->prepare("SELECT date, units_consumed, rate_per_unit, created_at FROM energy_usage WHERE user_id = ? ORDER BY date DESC");
$stmt->bind_param("i", $uid);
$stmt->execute();
$result = $stmt->get_result();

// Set CSV headers
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="energy_usage_' . date('Y-m-d') . '.csv"');

$output = fopen('php://output', 'w');

// CSV header row
fputcsv($output, ['Date', 'Units (kWh)', 'Rate (₹)', 'Cost (₹)', 'Recorded At']);

// Data rows
while ($row = $result->fetch_assoc()) {
    $cost = $row['units_consumed'] * $row['rate_per_unit'];
    fputcsv($output, [
        $row['date'],
        $row['units_consumed'],
        $row['rate_per_unit'],
        number_format($cost, 2),
        $row['created_at']
    ]);
}

fclose($output);
exit();
?>
