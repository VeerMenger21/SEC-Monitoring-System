<?php
include "auth_check.php";
include "../config.php";

$uid       = $_SESSION['user_id'];
$name      = $_POST['appliance_name'];
$wattage   = $_POST['wattage'];
$hours     = $_POST['hours'];
$date      = $_POST['date'];

$stmt = $conn->prepare("INSERT INTO appliance_usage (user_id, appliance_name, wattage, hours_used, date) VALUES (?,?,?,?,?)");
$stmt->bind_param("isdds", $uid, $name, $wattage, $hours, $date);

if ($stmt->execute()) {
    echo "<script>
    alert('Appliance usage saved!');
    window.location.href='../dashboard.php';
    </script>";
} else {
    echo "<script>
    alert('Error saving appliance data');
    window.location.href='../dashboard.php';
    </script>";
}
?>
