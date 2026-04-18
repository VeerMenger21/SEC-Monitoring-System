<?php
include "auth_check.php";
include "../config.php";

$id = $_GET['id'];
$uid = $_SESSION['user_id'];

// Only allow deleting own data (or admin can delete any)
if ($_SESSION['role'] === 'admin') {
    $stmt = $conn->prepare("UPDATE energy_usage SET is_deleted = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
} else {
    $stmt = $conn->prepare("UPDATE energy_usage SET is_deleted = 1 WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $uid);
}

if ($stmt->execute()) {
    $redirect = ($_SESSION['role'] === 'admin') ? '../admin.php' : '../dashboard.php';
    echo "<script>
    alert('Entry deleted');
    window.location.href='" . $redirect . "';
    </script>";
} else {
    echo "<script>
    alert('Error deleting entry');
    history.back();
    </script>";
}
?>
