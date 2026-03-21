/* ============================================
   Dark Mode Toggle — stored in localStorage
   ============================================ */

function updateDarkModeIcon(isDark) {
    document.querySelectorAll(".dark-toggle").forEach(function(el) {
        el.innerText = isDark ? "☀️" : "🌙";
    });
}

function toggleDarkMode() {
    document.body.classList.toggle("dark");
    const isDark = document.body.classList.contains("dark");

    if (isDark) {
        localStorage.setItem("darkMode", "on");
    } else {
        localStorage.setItem("darkMode", "off");
    }
    
    updateDarkModeIcon(isDark);
}

// Apply saved preference on page load
(function () {
    const isDark = localStorage.getItem("darkMode") === "on";
    if (isDark) {
        document.body.classList.add("dark");
    }
    // Update the icon immediately
    updateDarkModeIcon(isDark);
})();
