/* ============================================
   Dark Mode Toggle — stored in localStorage
   ============================================ */

function toggleDarkMode() {
    document.body.classList.toggle("dark");

    if (document.body.classList.contains("dark")) {
        localStorage.setItem("darkMode", "on");
    } else {
        localStorage.setItem("darkMode", "off");
    }
}

// Apply saved preference on page load
(function () {
    if (localStorage.getItem("darkMode") === "on") {
        document.body.classList.add("dark");
    }
})();
