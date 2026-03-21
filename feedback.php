<?php
include "php/auth_check.php";
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback — Smart Energy System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <h1>⚡ Smart Energy Consumption Monitoring System</h1>
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="dashboard.php">Dashboard</a>
    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <a href="admin.php">Admin</a>
    <?php endif; ?>
    <a href="reports.php">Reports</a>
    <a href="feedback.php" class="active">Feedback</a>
    <a href="php/logout.php">Logout (<?php echo htmlspecialchars($username); ?>)</a>
    <a href="javascript:void(0)" onclick="toggleDarkMode()" class="dark-toggle" title="Toggle Dark Mode">🌙</a>
</nav>

<div class="main">
    <div class="form-box">

        <h2 style="text-align:center; color:#0a3d62; margin-bottom:20px;">Feedback Form</h2>

        <form action="php/save_feedback.php" method="POST" onsubmit="return validateFeedback()">

            <!-- Username shown automatically -->
            <label>Logged in as</label>
            <input type="text" value="<?php echo htmlspecialchars($username); ?>" disabled>

            <label>Feedback Type</label>
            <select name="type" id="fbType">
                <option value="">Select Type</option>
                <option value="Suggestion">Suggestion</option>
                <option value="Bug Report">Bug Report</option>
                <option value="General">General</option>
            </select>
            <div class="error-msg" id="fbTypeErr"></div>

            <label>Your Feedback</label>
            <textarea name="message" id="fbMessage" placeholder="Enter your feedback (min 10 characters)"></textarea>
            <div class="error-msg" id="fbMessageErr"></div>

            <label style="text-align:center; display:block; margin-top:16px;">Rating</label>
            <div class="stars">
                <span class="star" data-value="1">&#9733;</span>
                <span class="star" data-value="2">&#9733;</span>
                <span class="star" data-value="3">&#9733;</span>
                <span class="star" data-value="4">&#9733;</span>
                <span class="star" data-value="5">&#9733;</span>
            </div>
            <div class="error-msg" id="fbRatingErr" style="text-align:center;"></div>

            <input type="hidden" name="rating" id="fbRating">

            <button type="submit" class="btn btn-primary">Submit Feedback</button>
        </form>

    </div>
</div>

<footer>
    <p>&copy; 2026 Smart Energy Consumption Monitoring System</p>
</footer>

<script src="js/feedback.js"></script>
<script src="js/darkmode.js"></script>
</body>
</html>