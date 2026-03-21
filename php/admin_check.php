<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<script>
    alert('Access denied. Admin only.');
    window.location.href='../dashboard.php';
    </script>";
    exit();
}
?>
