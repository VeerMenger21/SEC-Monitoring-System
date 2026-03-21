<?php
include "auth_check.php";
include "../config.php";

$uid  = $_SESSION['user_id'];
$date  = $_POST['date'];
$units = $_POST['units'];
$rate  = $_POST['rate'];

$stmt = $conn->prepare("INSERT INTO energy_usage (user_id, date, units_consumed, rate_per_unit) VALUES (?,?,?,?)");
$stmt->bind_param("isdd", $uid, $date, $units, $rate);

if ($stmt->execute()) {
    echo "<script>
    alert('Usage saved successfully!');
    window.location.href='../dashboard.php';
    </script>";
} else {
    echo "<script>
    alert('Error saving usage');
    window.location.href='../dashboard.php';
    </script>";
}
?>
