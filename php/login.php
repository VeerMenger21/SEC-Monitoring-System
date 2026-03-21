<?php
session_start();
include "../config.php";

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['username']  = $user['username'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role']      = $user['role'] ?? 'user';

        // Fallback: project creator always gets admin
        if ($user['username'] === 'veermenger') {
            $_SESSION['role'] = 'admin';
        }

        // Determine redirect target
        $redirect = '../dashboard.php';
        if (isset($_POST['redirect']) && !empty($_POST['redirect'])) {
            $redirect = '../' . basename($_POST['redirect']);
        }

        echo "<script>
        alert('Login Successful!');
        window.location.href='" . $redirect . "';
        </script>";
    } else {
        echo "<script>
        alert('Incorrect password');
        window.location.href='../auth.php';
        </script>";
    }
} else {
    echo "<script>
    alert('Username not found. Please register.');
    window.location.href='../auth.php';
    </script>";
}
?>
