<?php
// ============================================================
// SESSION & COOKIE CLEANUP — logout.php
// ============================================================
// When a user logs out, we must:
// 1. Destroy the SESSION (server-side data)
// 2. Clear COOKIES (client-side data)
// ============================================================

session_start();  // Resume the session so we can destroy it

// ─── STEP 1: Destroy Session ────────────────────────────────
// session_unset()  → Removes all session variables
// session_destroy()→ Destroys the session data on the server
session_unset();
session_destroy();

// ─── STEP 2: Clear Cookies ──────────────────────────────────
// To delete a cookie, set its expiry to a past time.
// time() - 3600 = one hour ago → browser will remove the cookie.

// Clear "Remember Me" cookie
setcookie("remember_user", "", time() - 3600, "/");

// Clear "Last Login" cookie
setcookie("last_login", "", time() - 3600, "/");

// Clear the PHP session cookie (PHPSESSID)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),        // Cookie name: PHPSESSID
        '',                    // Empty value
        time() - 3600,         // Expired
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

echo "<script>
alert('Logged out successfully');
window.location.href='../index.php';
</script>";
?>
