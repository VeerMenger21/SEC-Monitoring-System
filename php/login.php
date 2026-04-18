<?php
// ============================================================
// SESSION & COOKIE IMPLEMENTATION — login.php
// ============================================================
// SESSION: Server-side storage. Data is stored on the server and
//          linked to the browser via a session ID cookie (PHPSESSID).
// COOKIE:  Client-side storage. Data is stored in the user's browser
//          and sent with every HTTP request to the server.
// ============================================================

session_start();  // Start or resume the session
include "../config.php";

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND is_deleted = 0");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {

        // ─── SESSION VARIABLES ───────────────────────────────
        // Storing user data in the session (server-side).
        // This data persists across pages until the session ends.
        $_SESSION['user_id']       = $user['id'];
        $_SESSION['username']      = $user['username'];
        $_SESSION['user_name']     = $user['name'];
        $_SESSION['role']          = $user['role'] ?? 'user';
        
        // Set timezone specifically so it shows the correct local time
        date_default_timezone_set('Asia/Kolkata');
        $_SESSION['login_time']    = date('Y-m-d h:i A');  // Track login time in session (12-hour format)

        // Fallback: project creator always gets admin
        if ($user['username'] === 'veermenger') {
            $_SESSION['role'] = 'admin';
        }

        // ─── COOKIE: "Remember Me" ──────────────────────────
        // If the user checked "Remember Me", store their username
        // in a cookie that lasts 30 days. This pre-fills the login
        // form next time they visit.
        if (isset($_POST['remember']) && $_POST['remember'] == '1') {
            // setcookie(name, value, expire, path)
            // time() + (86400 * 30) = current time + 30 days in seconds
            setcookie("remember_user", $user['username'], time() + (86400 * 30), "/");
            
            // Extend the PHP session cookie to keep the user logged in across browser restarts
            $params = session_get_cookie_params();
            setcookie(session_name(), session_id(), time() + (86400 * 30), $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        } else {
            // If not checked, clear any existing remember cookie
            setcookie("remember_user", "", time() - 3600, "/");
            
            // Explicitly force the PHPSESSID to be a session-only cookie 
            $params = session_get_cookie_params();
            setcookie(session_name(), session_id(), 0, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }

        // ─── COOKIE: Last Login Timestamp ────────────────────
        // Store the last login time in a cookie (7 days expiry)
        // so we can display "Last login: ..." on the dashboard.
        date_default_timezone_set('Asia/Kolkata');
        setcookie("last_login", date('Y-m-d h:i A'), time() + (86400 * 7), "/");

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
