<?php
session_start();
include "../config.php";

$name     = $_POST['name'];
$username = $_POST['username'];
$email    = $_POST['email'];
$dob      = $_POST['dob'];
$phone    = $_POST['phone'];
$address  = $_POST['address'];
$city     = $_POST['city'];
$zip      = $_POST['zip'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Check if user already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>
    alert('Username or email already exists');
    window.location.href='../auth.php';
    </script>";
} else {
    $stmt = $conn->prepare("INSERT INTO users (name, username, email, dob, phone, address, city, zip, password) VALUES (?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssssss", $name, $username, $email, $dob, $phone, $address, $city, $zip, $password);

    if ($stmt->execute()) {
        echo "<script>
        alert('Registration successful! Please login.');
        window.location.href='../auth.php';
        </script>";
    } else {
        echo "<script>
        alert('Registration failed. Please try again.');
        window.location.href='../auth.php';
        </script>";
    }
}
?>
