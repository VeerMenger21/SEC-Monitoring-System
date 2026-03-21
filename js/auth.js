/* ============================================
   Auth Page — Validation (ALL logic in JS)
   ============================================ */

// --- Tab switching ---
function showLogin() {
    document.getElementById("loginForm").style.display = "block";
    document.getElementById("registerForm").style.display = "none";
    document.getElementById("loginTab").classList.add("active");
    document.getElementById("registerTab").classList.remove("active");
}

function showRegister() {
    document.getElementById("loginForm").style.display = "none";
    document.getElementById("registerForm").style.display = "block";
    document.getElementById("registerTab").classList.add("active");
    document.getElementById("loginTab").classList.remove("active");
}

// --- Password toggle ---
function togglePassword(id) {
    let el = document.getElementById(id);
    el.type = (el.type === "password") ? "text" : "password";
}

// --- Validation helper ---
function check(input, errId, condition, message) {
    let errEl = document.getElementById(errId);
    if (!condition) {
        errEl.innerText = message;
        input.classList.add("invalid");
        input.classList.remove("valid");
        return false;
    } else {
        errEl.innerText = "";
        input.classList.remove("invalid");
        input.classList.add("valid");
        return true;
    }
}

// --- Login validation ---
function validateLogin() {
    let user = document.getElementById("loginUser");
    let pass = document.getElementById("loginPass");

    let valid = true;
    valid &= check(user, "loginUserErr", user.value.length >= 4, "Minimum 4 characters");
    valid &= check(pass, "loginPassErr", pass.value.length >= 6, "Minimum 6 characters");

    if (!valid) { alert("Please correct all fields"); return false; }
    return true;
}

// --- Register validation ---
function validateRegister() {
    let name    = document.getElementById("regName");
    let user    = document.getElementById("regUser");
    let email   = document.getElementById("regEmail");
    let dob     = document.getElementById("regDob");
    let phone   = document.getElementById("regPhone");
    let zip     = document.getElementById("regZip");
    let pass    = document.getElementById("regPass");
    let confirm = document.getElementById("regConfirm");

    let valid = true;

    valid &= check(name, "regNameErr", /^[A-Za-z ]{2,}$/.test(name.value), "Only letters, min 2 chars");
    valid &= check(user, "regUserErr", user.value.length >= 4, "Minimum 4 characters");
    valid &= check(email, "regEmailErr", /^[^\s@]+@[^\s@]+\.[a-z]{2,}$/.test(email.value), "Invalid email");

    // Age check — must be 18+
    let birth = new Date(dob.value);
    let today = new Date();
    let age = today.getFullYear() - birth.getFullYear();
    valid &= check(dob, "regDobErr", dob.value !== "" && age >= 18, "Must be 18+");

    valid &= check(phone, "regPhoneErr", /^[0-9]{10}$/.test(phone.value), "Must be 10 digits");
    valid &= check(zip, "regZipErr", /^[0-9]{6}$/.test(zip.value), "Must be 6 digits");
    valid &= check(pass, "regPassErr", pass.value.length >= 6, "Minimum 6 characters");
    valid &= check(confirm, "regConfirmErr", confirm.value === pass.value && confirm.value !== "", "Passwords don't match");

    if (!valid) { alert("Please correct all fields"); return false; }
    return true;
}

// --- Live validation on input ---
document.addEventListener("DOMContentLoaded", function() {
    // Login live
    let lu = document.getElementById("loginUser");
    let lp = document.getElementById("loginPass");
    if (lu) lu.oninput = () => check(lu, "loginUserErr", lu.value.length >= 4, "Minimum 4 characters");
    if (lp) lp.oninput = () => check(lp, "loginPassErr", lp.value.length >= 6, "Minimum 6 characters");

    // Register live
    let rn = document.getElementById("regName");
    let ru = document.getElementById("regUser");
    let re = document.getElementById("regEmail");
    let rp = document.getElementById("regPhone");
    let rz = document.getElementById("regZip");
    let rpa = document.getElementById("regPass");
    let rc = document.getElementById("regConfirm");

    if (rn)  rn.oninput = () => check(rn, "regNameErr", /^[A-Za-z ]{2,}$/.test(rn.value), "Only letters, min 2 chars");
    if (ru)  ru.oninput = () => check(ru, "regUserErr", ru.value.length >= 4, "Minimum 4 characters");
    if (re)  re.oninput = () => check(re, "regEmailErr", /^[^\s@]+@[^\s@]+\.[a-z]{2,}$/.test(re.value), "Invalid email");
    if (rp)  rp.oninput = () => check(rp, "regPhoneErr", /^[0-9]{10}$/.test(rp.value), "Must be 10 digits");
    if (rz)  rz.oninput = () => check(rz, "regZipErr", /^[0-9]{6}$/.test(rz.value), "Must be 6 digits");
    if (rpa) rpa.oninput = () => check(rpa, "regPassErr", rpa.value.length >= 6, "Minimum 6 characters");
    if (rc)  rc.oninput = () => check(rc, "regConfirmErr", rc.value === rpa.value && rc.value !== "", "Passwords don't match");
});
