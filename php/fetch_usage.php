<?php
include "auth_check.php";
include "../config.php";

$uid = $_SESSION['user_id'];

// Fetch ALL usage rows for this user (JS does the calculations)
$stmt = $conn->prepare("SELECT id, date, units_consumed, rate_per_unit, created_at FROM energy_usage WHERE user_id = ? AND is_deleted = 0 ORDER BY date DESC");
$stmt->bind_param("i", $uid);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
?>
