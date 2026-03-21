/* ============================================
   Feedback Page — Star Rating + Validation
   ============================================ */

let selectedRating = 0;

document.addEventListener("DOMContentLoaded", function () {

    // Star click handler
    let stars = document.querySelectorAll(".star");
    stars.forEach(function (star) {
        star.addEventListener("click", function () {
            selectedRating = parseInt(this.getAttribute("data-value"));
            document.getElementById("fbRating").value = selectedRating;
            updateStars(selectedRating);
            document.getElementById("fbRatingErr").innerText = "";
        });

        // Hover effect
        star.addEventListener("mouseenter", function () {
            updateStars(parseInt(this.getAttribute("data-value")));
        });

        star.addEventListener("mouseleave", function () {
            updateStars(selectedRating);
        });
    });

    // Live validation
    let type = document.getElementById("fbType");
    let msg  = document.getElementById("fbMessage");

    type.onchange = function () {
        if (type.value === "") {
            document.getElementById("fbTypeErr").innerText = "Please select a type";
        } else {
            document.getElementById("fbTypeErr").innerText = "";
        }
    };

    msg.oninput = function () {
        if (msg.value.length < 10) {
            document.getElementById("fbMessageErr").innerText = "Minimum 10 characters";
        } else {
            document.getElementById("fbMessageErr").innerText = "";
        }
    };
});

function updateStars(rating) {
    let stars = document.querySelectorAll(".star");
    stars.forEach(function (star) {
        if (parseInt(star.getAttribute("data-value")) <= rating) {
            star.classList.add("active");
        } else {
            star.classList.remove("active");
        }
    });
}

function validateFeedback() {
    let type    = document.getElementById("fbType");
    let message = document.getElementById("fbMessage");
    let valid   = true;

    if (type.value === "") {
        document.getElementById("fbTypeErr").innerText = "Please select a type";
        valid = false;
    }

    if (message.value.length < 10) {
        document.getElementById("fbMessageErr").innerText = "Minimum 10 characters";
        valid = false;
    }

    if (selectedRating === 0) {
        document.getElementById("fbRatingErr").innerText = "Please select a rating";
        valid = false;
    }

    if (!valid) alert("Please correct all fields");
    return valid;
}
