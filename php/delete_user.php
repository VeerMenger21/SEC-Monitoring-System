<?php
include "admin_check.php";
include "../config.php";

$id = $_GET['id'];

// Delete user's related data first (foreign key constraints)
$conn->prepare("DELETE FROM energy_usage WHERE user_id = ?")->bind_param("i", $id);
$conn->prepare("DELETE FROM appliance_usage WHERE user_id = ?")->bind_param("i", $id);
$conn->prepare("DELETE FROM feedback WHERE user_id = ?")->bind_param("i", $id);

// Execute the deletes
$stmt1 = $conn->prepare("DELETE FROM energy_usage WHERE user_id = ?");
$stmt1->bind_param("i", $id);
$stmt1->execute();

$stmt2 = $conn->prepare("DELETE FROM appliance_usage WHERE user_id = ?");
$stmt2->bind_param("i", $id);
$stmt2->execute();

$stmt3 = $conn->prepare("DELETE FROM feedback WHERE user_id = ?");
$stmt3->bind_param("i", $id);
$stmt3->execute();

// Now delete the user
$stmt4 = $conn->prepare("DELETE FROM users WHERE id = ?");
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
