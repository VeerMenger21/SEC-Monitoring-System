<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register — Smart Energy System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <h1>⚡ Smart Energy Consumption Monitoring System</h1>
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="auth.php" class="active">Register / Login</a>
    <a href="javascript:void(0)" onclick="toggleDarkMode()" class="dark-toggle" title="Toggle Dark Mode">🌙</a>
</nav>

<div class="main">
    <div class="form-box">

        <div class="tabs">
            <button id="loginTab" class="active" onclick="showLogin()">Login</button>
            <button id="registerTab" onclick="showRegister()">Register</button>
        </div>

        <!-- ========== LOGIN FORM ========== -->
        <form id="loginForm" action="php/login.php" method="POST" onsubmit="return validateLogin()">

            <?php if(isset($_GET['redirect'])): ?>
            <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_GET['redirect']); ?>">
            <?php endif; ?>

            <label>Username</label>
            <input type="text" name="username" id="loginUser" placeholder="Enter username">
            <div class="error-msg" id="loginUserErr"></div>

            <label>Password</label>
            <input type="password" name="password" id="loginPass" placeholder="Enter password">
            <div class="error-msg" id="loginPassErr"></div>

            <div class="pass-row">
                <input type="checkbox" onclick="togglePassword('loginPass')"> Show Password
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <!-- ========== REGISTER FORM ========== -->
        <form id="registerForm" action="php/register.php" method="POST" onsubmit="return validateRegister()" style="display:none;">

            <label>Full Name</label>
            <input type="text" name="name" id="regName" placeholder="Full Name">
            <div class="error-msg" id="regNameErr"></div>

            <label>Username</label>
            <input type="text" name="username" id="regUser" placeholder="Username">
            <div class="error-msg" id="regUserErr"></div>

            <label>Email</label>
            <input type="email" name="email" id="regEmail" placeholder="Email">
            <div class="error-msg" id="regEmailErr"></div>

            <label>Date of Birth</label>
            <input type="date" name="dob" id="regDob">
            <div class="error-msg" id="regDobErr"></div>

            <label>Phone</label>
            <input type="tel" name="phone" id="regPhone" placeholder="10 digit phone">
            <div class="error-msg" id="regPhoneErr"></div>

            <label>Address</label>
            <textarea name="address" placeholder="Address"></textarea>

            <label>City</label>
            <input type="text" name="city" placeholder="City">

            <label>Zip Code</label>
            <input type="text" name="zip" id="regZip" placeholder="6 digit zip">
            <div class="error-msg" id="regZipErr"></div>

            <label>Password</label>
            <input type="password" name="password" id="regPass" placeholder="Min 6 characters">
            <div class="error-msg" id="regPassErr"></div>

            <label>Confirm Password</label>
            <input type="password" id="regConfirm" placeholder="Confirm password">
            <div class="error-msg" id="regConfirmErr"></div>

            <div class="pass-row">
                <input type="checkbox" onclick="togglePassword('regPass'); togglePassword('regConfirm');"> Show Password
            </div>

            <button type="submit" class="btn btn-primary">Register</button>
        </form>

    </div>
</div>

<footer>
    <p>&copy; 2026 Smart Energy Consumption Monitoring System</p>
</footer>

<script src="js/auth.js"></script>
<script src="js/darkmode.js"></script>
</body>
</html>
