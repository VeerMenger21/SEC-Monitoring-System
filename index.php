<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Energy Consumption Monitoring System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <h1>⚡ Smart Energy Consumption Monitoring System</h1>
</header>

<nav>
    <a href="index.php" class="active">Home</a>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="dashboard.php">Dashboard</a>
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <a href="admin.php">Admin</a>
        <?php endif; ?>
        <a href="reports.php">Reports</a>
        <a href="feedback.php">Feedback</a>
        <a href="php/logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
    <?php else: ?>
        <a href="auth.php">Register / Login</a>
    <?php endif; ?>
    <a href="javascript:void(0)" onclick="toggleDarkMode()" class="dark-toggle" title="Toggle Dark Mode">🌙</a>
</nav>

<div class="main">

    <!-- HERO SECTION -->
    <div class="hero">
        <div class="hero-content">
            <h2>Welcome to the Smart Energy Consumption Monitoring System</h2>
            <p>
                Track and manage your electricity usage efficiently. This platform provides
                real-time monitoring of energy consumption, detailed analytics, and alerts to 
                help reduce electricity wastage. Analyze consumption patterns and make smarter 
                decisions to save energy and promote sustainable usage.
            </p>

            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php" class="btn btn-primary" style="width:auto; display:inline-block;">Go to Dashboard →</a>
            <?php else: ?>
                <a href="auth.php" class="btn btn-primary" style="width:auto; display:inline-block;">Get Started — Register Now →</a>
            <?php endif; ?>
        </div>

        <div class="hero-img">
            <img src="assets/energy.jpg" alt="Energy Monitoring">
        </div>
    </div>

    <!-- FEATURES -->
    <?php
        $loggedIn = isset($_SESSION['user_id']);
        function featureLink($page, $loggedIn) {
            return $loggedIn ? $page : "auth.php?redirect=" . $page;
        }
    ?>
    <div class="feature-grid">
        <a href="<?php echo featureLink('dashboard.php#section-monitoring', $loggedIn); ?>" class="feature-card-link">
        <div class="feature-card">
            <div class="icon">📊</div>
            <h3>Real-Time Monitoring</h3>
            <p>Track electricity consumption instantly and monitor your daily and monthly energy usage.</p>
        </div>
        </a>

        <a href="<?php echo featureLink('dashboard.php#section-analytics', $loggedIn); ?>" class="feature-card-link">
        <div class="feature-card">
            <div class="icon">📈</div>
            <h3>Energy Analytics</h3>
            <p>View interactive charts and statistics for your daily, monthly, and appliance-wise power usage.</p>
        </div>
        </a>

        <a href="<?php echo featureLink('dashboard.php#alertArea', $loggedIn); ?>" class="feature-card-link">
        <div class="feature-card">
            <div class="icon">🔔</div>
            <h3>Smart Alerts</h3>
            <p>Receive warnings when your consumption exceeds safe limits or spikes unusually.</p>
        </div>
        </a>

        <a href="<?php echo featureLink('dashboard.php#tipsSection', $loggedIn); ?>" class="feature-card-link">
        <div class="feature-card">
            <div class="icon">💡</div>
            <h3>Saving Tips</h3>
            <p>Get personalized energy-saving tips based on your actual consumption patterns.</p>
        </div>
        </a>

        <a href="<?php echo featureLink('dashboard.php#section-appliance', $loggedIn); ?>" class="feature-card-link">
        <div class="feature-card">
            <div class="icon">🔌</div>
            <h3>Appliance Tracking</h3>
            <p>Track individual appliance consumption to identify which devices use the most power.</p>
        </div>
        </a>

        <a href="<?php echo featureLink('reports.php', $loggedIn); ?>" class="feature-card-link">
        <div class="feature-card">
            <div class="icon">💰</div>
            <h3>Bill Estimation</h3>
            <p>See your estimated electricity bill in real-time based on current month usage and rate.</p>
        </div>
        </a>
    </div>

</div>

<footer>
    <p>&copy; 2026 Smart Energy Consumption Monitoring System. All Rights Reserved. | Created by Veer Menger</p>
</footer>

<script>
// Highlight clicked feature card for 2.5 seconds before navigating
document.querySelectorAll('.feature-card-link').forEach(function(link) {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        var card = this.querySelector('.feature-card');
        var href = this.getAttribute('href');

        card.classList.add('highlight');

        setTimeout(function() {
            card.classList.remove('highlight');
            window.location.href = href;
        }, 2500);
    });
});
</script>

<!-- COOKIE CONSENT BANNER -->
<div id="cookieConsent" class="cookie-banner">
    <p>🍪 This website uses cookies to enhance your experience. We use cookies for login sessions and remembering your preferences.</p>
    <button onclick="acceptCookies()" class="btn btn-primary" style="width:auto; padding:8px 20px; font-size:13px;">Accept Cookies</button>
</div>
<script>
function getCookie(name) {
    var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return match ? match[2] : null;
}
if (getCookie('site_cookie_consent') === 'accepted') {
    document.getElementById('cookieConsent').style.display = 'none';
}
function acceptCookies() {
    // Set site_cookie_consent as a persistent cookie (1 year)
    document.cookie = "site_cookie_consent=accepted;max-age=31536000;path=/";
    document.getElementById('cookieConsent').style.display = 'none';
}
</script>

<script src="js/darkmode.js"></script>
</body>
</html>
