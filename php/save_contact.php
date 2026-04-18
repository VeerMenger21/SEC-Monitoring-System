<?php
session_start();
include "../config.php";

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized request.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    // Since the name input is readonly when prefilled, we should just ensure we grab $_SESSION['username']
    // if $name somehow comes empty, or if we want to enforce it. Let's just use what's submitted or the session one.
    if(empty($name)) {
        $name = $_SESSION['username'];
    }
    
    $stmt = $conn->prepare("INSERT INTO contact_messages (user_id, name, email, subject, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $name, $email, $subject, $message);
    
    if ($stmt->execute()) {
        echo "<script>
            alert('Your message has been securely submitted!');
            window.location.href = '../contact.php';
        </script>";
    } else {
        echo "<script>
            alert('System Error: Unable to save message. Please try again.');
            window.history.back();
        </script>";
    }
}
?>
