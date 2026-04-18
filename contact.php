<?php
include "php/auth_check.php";
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us — Smart Energy System</title>
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
    <a href="feedback.php">Feedback</a>
    <a href="contact.php" class="active">Contact</a>
    <a href="php/logout.php">Logout (<?php echo htmlspecialchars($username); ?>)</a>
    <a href="javascript:void(0)" onclick="toggleDarkMode()" class="dark-toggle" title="Toggle Dark Mode">🌙</a>
</nav>

<div class="main">
    <div class="form-box">
        <h2 style="text-align:center; color:#0a3d62; margin-bottom:20px;">Contact Us</h2>
        <p style="text-align:center; margin-bottom:20px; line-height: 1.5;">Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
        
        <form action="php/save_contact.php" method="POST">
            <label>Name</label>
            <input type="text" name="name" placeholder="Your Name" required value="<?php echo htmlspecialchars($username); ?>" <?php if($username) echo 'readonly'; ?>>
            
            <label>Email</label>
            <input type="email" name="email" placeholder="Your Email Address" required>
            
            <label>Subject</label>
            <input type="text" name="subject" placeholder="Subject" required>
            
            <label>Message</label>
            <textarea name="message" placeholder="Write your message here" style="height: 100px; padding: 10px; width: 100%; border: 1px solid #ccc; border-radius: 5px;" required></textarea>
            
            <button type="submit" class="btn btn-primary" style="margin-top: 15px;">Send Message</button>
        </form>
        
        <div style="margin-top: 30px; text-align: center; line-height: 1.8;">
            <p><strong>Email:</strong> veermenger@gmail.com</p>
            <p><strong>Phone:</strong> +91 8850039431</p>
            <p><strong>Address:</strong> K. J. Somaiya College of Engineering, Vidyavihar (East), Mumbai - 400077, Maharashtra, India</p>
        </div>
    </div>
</div>

<footer>
    <p>&copy; 2026 Smart Energy Consumption Monitoring System | Created by Veer Menger</p>
</footer>

<script src="js/darkmode.js"></script>
</body>
</html>
