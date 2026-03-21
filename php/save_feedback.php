<?php
include "auth_check.php";
include "../config.php";

$uid     = $_SESSION['user_id'];
$type    = $_POST['type'];
$message = $_POST['message'];
$rating  = $_POST['rating'];

$stmt = $conn->prepare("INSERT INTO feedback (user_id, type, message, rating) VALUES (?,?,?,?)");
$stmt->bind_param("issi", $uid, $type, $message, $rating);

if ($stmt->execute()) {
    echo "<script>
    alert('Feedback submitted successfully!');
    window.location.href='../feedback.php';
    </script>";
} else {
    echo "<script>
    alert('Error submitting feedback');
    window.location.href='../feedback.php';
    </script>";
}
?>
