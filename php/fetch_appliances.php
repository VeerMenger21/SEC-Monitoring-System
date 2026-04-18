<?php
include "auth_check.php";
include "../config.php";

$uid = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT id, appliance_name, wattage, hours_used, date, created_at FROM appliance_usage WHERE user_id = ? AND is_deleted = 0 ORDER BY date DESC");
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
