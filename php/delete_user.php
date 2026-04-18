<?php
include "admin_check.php";
include "../config.php";

$id = $_GET['id'];

// Soft delete user's related data first
$stmt1 = $conn->prepare("UPDATE energy_usage SET is_deleted = 1 WHERE user_id = ?");
$stmt1->bind_param("i", $id);
$stmt1->execute();

$stmt2 = $conn->prepare("UPDATE appliance_usage SET is_deleted = 1 WHERE user_id = ?");
$stmt2->bind_param("i", $id);
$stmt2->execute();

$stmt3 = $conn->prepare("UPDATE feedback SET is_deleted = 1 WHERE user_id = ?");
$stmt3->bind_param("i", $id);
$stmt3->execute();

$stmt3a = $conn->prepare("UPDATE contact_messages SET is_deleted = 1 WHERE user_id = ?");
$stmt3a->bind_param("i", $id);
$stmt3a->execute();

// Now soft delete the user
$stmt4 = $conn->prepare("UPDATE users SET is_deleted = 1 WHERE id = ?");
$stmt4->bind_param("i", $id);

if ($stmt4->execute()) {
    echo "<script>
    alert('User deleted successfully');
    window.location.href='../admin.php';
    </script>";
} else {
    echo "<script>
    alert('Error deleting user');
    window.location.href='../admin.php';
    </script>";
}
?>
