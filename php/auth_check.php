<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>
    alert('Please login first');
    window.location.href='index.php';
    </script>";
    exit();
}
?>
