/* ============================================
   Dashboard JS — ALL logic lives here
   PHP only provides raw data via usageData and applianceData
   ============================================ */

document.addEventListener("DOMContentLoaded", function () {

    // --- Scroll to section and highlight if hash present ---
    if (window.location.hash) {
        var target = document.querySelector(window.location.hash);
        if (target) {
            setTimeout(function () {
                target.scrollIntoView({ behavior: "smooth", block: "center" });
                target.classList.add("highlight-section");
                setTimeout(function () {
                    target.classList.remove("highlight-section");
                }, 3000);
            }, 300);
        }
    }

    // --- Helper: get today's date string ---
    const today = new Date();
    const todayStr = today.toISOString().split("T")[0]; // "YYYY-MM-DD"
    const currentMonth = today.getMonth() + 1;
    const currentYear = today.getFullYear();

    // Set date labels
    document.getElementById("todayDate").innerText = todayStr;
    document.getElementById("monthLabel").innerText = today.toLocaleString("default", { month: "long", year: "numeric" });

    // =======================================
    // 1. CALCULATE TOTALS FROM RAW DATA
    // =======================================

    let todayTotal = 0;
    let monthTotal = 0;
    let lastMonthTotal = 0;
    let latestRate = 7.5;

    // Per-day aggregation for chart
    let dailyMap = {};

    usageData.forEach(function (row) {
        let d = new Date(row.date);
        let units = parseFloat(row.units_consumed);
        let rate = parseFloat(row.rate_per_unit);

        // Today
        if (row.date === todayStr) {
            todayTotal += units;
        }

        // This month
        if (d.getMonth() + 1 === currentMonth && d.getFullYear() === currentYear) {
            monthTotal += units;
            latestRate = rate;
        }

        // Last month
        let lastMonth = currentMonth === 1 ? 12 : currentMonth - 1;
        let lastMonthYear = currentMonth === 1 ? currentYear - 1 : currentYear;
        if (d.getMonth() + 1 === lastMonth && d.getFullYear() === lastMonthYear) {
            lastMonthTotal += units;
        }

        // Daily aggregation
        if (!dailyMap[row.date]) dailyMap[row.date] = 0;
        dailyMap[row.date] += units;
    });

    // Bill
    let estBill = (monthTotal * latestRate).toFixed(2);

    // Percentage change
    let changePercent = 0;
    if (lastMonthTotal > 0) {
        changePercent = (((monthTotal - lastMonthTotal) / lastMonthTotal) * 100).toFixed(1);
    }

    // =======================================
    // 2. UPDATE DASHBOARD CARDS
    // =======================================

    document.getElementById("todayUsage").innerText = todayTotal.toFixed(2) + " kWh";
    document.getElementById("monthUsage").innerText = monthTotal.toFixed(2) + " kWh";
    document.getElementById("estBill").innerText = "₹" + estBill;

    let changeEl = document.getElementById("changePercent");
    let changeLabelEl = document.getElementById("changeLabel");

    if (changePercent > 0) {
        changeEl.innerText = "▲ " + Math.abs(changePercent) + "%";
        changeEl.classList.add("status-red");
        changeLabelEl.innerText = "Increase from last month";
    } else if (changePercent < 0) {
        changeEl.innerText = "▼ " + Math.abs(changePercent) + "%";
        changeEl.classList.add("status-green");
        changeLabelEl.innerText = "Decrease from last month";
    } else {
        changeEl.innerText = "— 0%";
        changeLabelEl.innerText = "No previous data";
    }

    // =======================================
    // 3. ALERTS & WARNINGS (JS logic)
    // =======================================

    let alertArea = document.getElementById("alertArea");

    // Calculate daily average
    let dailyValues = Object.values(dailyMap);
    let avgDaily = 0;
    if (dailyValues.length > 0) {
        avgDaily = dailyValues.reduce((a, b) => a + b, 0) / dailyValues.length;
    }

    // Alert: today exceeds average
    if (todayTotal > avgDaily && avgDaily > 0) {
        showAlert("danger", "⚠️ Today's usage (" + todayTotal.toFixed(2) + " kWh) exceeds your daily average (" + avgDaily.toFixed(2) + " kWh)!");
    }

    // Warning: monthly increase > 20%
    if (changePercent > 20) {
        showAlert("warning", "⚠️ Your usage increased by " + changePercent + "% compared to last month!");
    }

    // Success: decrease
    if (changePercent < 0) {
        showAlert("success", "✅ Great! Your usage decreased by " + Math.abs(changePercent) + "% compared to last month.");
    }

    // Info: no data
    if (usageData.length === 0) {
        showAlert("info", "📝 No usage data yet. Start by logging your energy usage below!");
    }

    function showAlert(type, message) {
        let div = document.createElement("div");
        div.className = "alert alert-" + type + " show";
        div.innerText = message;
        alertArea.appendChild(div);
    }

    // =======================================
    // 4. USAGE HISTORY TABLE
    // =======================================

    let tbody = document.getElementById("usageTableBody");
    if (usageData.length > 0) {
        tbody.innerHTML = "";
        // Show last 10
        let displayData = usageData.slice(0, 10);
        displayData.forEach(function (row) {
            let units = parseFloat(row.units_consumed);
            let rate = parseFloat(row.rate_per_unit);
            let cost = (units * rate).toFixed(2);

            let tr = document.createElement("tr");
            tr.innerHTML =
                "<td>" + row.date + "</td>" +
                "<td>" + units.toFixed(2) + "</td>" +
                "<td>₹" + rate.toFixed(2) + "</td>" +
                "<td>₹" + cost + "</td>" +
                "<td class='no-print'><a href='php/delete_usage.php?id=" + row.id + "' class='btn-delete' onclick='return confirm(\"Delete this entry?\")'>Delete</a></td>";
            tbody.appendChild(tr);
        });
    }

    // =======================================
    // 5. BAR CHART — Last 7 Days
    // =======================================

    // Get last 7 days from dailyMap
    let sortedDays = Object.keys(dailyMap).sort();
    let last7Days = sortedDays.slice(-7);
    let last7Values = last7Days.map(function (d) { return dailyMap[d]; });

    let barCtx = document.getElementById("barChart").getContext("2d");
    new Chart(barCtx, {
        type: "bar",
        data: {
            labels: last7Days,
            datasets: [{
                label: "kWh Used",
                data: last7Values,
                backgroundColor: last7Values.map(function (v) {
                    return v > avgDaily ? "#e74c3c" : "#1e3799";
                }),
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, title: { display: true, text: "kWh" } }
            }
        }
    });

    // =======================================
    // 6. PIE CHART — Appliance Breakdown
    // =======================================

    // JS calculates kWh for each appliance: (wattage * hours) / 1000
    let applianceMap = {};
    applianceData.forEach(function (row) {
        let kwh = (parseFloat(row.wattage) * parseFloat(row.hours_used)) / 1000;
        let name = row.appliance_name;
        if (!applianceMap[name]) applianceMap[name] = 0;
        applianceMap[name] += kwh;
    });

    let appNames = Object.keys(applianceMap);
    let appValues = Object.values(applianceMap).map(function (v) { return parseFloat(v.toFixed(2)); });

    let pieCtx = document.getElementById("pieChart").getContext("2d");

    if (appNames.length > 0) {
        let colors = ["#e74c3c", "#3498db", "#2ecc71", "#f39c12", "#9b59b6", "#1abc9c", "#e67e22", "#34495e"];
        new Chart(pieCtx, {
            type: "doughnut",
            data: {
                labels: appNames,
                datasets: [{
                    data: appValues,
                    backgroundColor: colors.slice(0, appNames.length)
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: "bottom" }
                }
            }
        });
    } else {
        document.getElementById("noApplianceMsg").style.display = "block";
    }

    // =======================================
    // 7. ENERGY SAVING TIPS (JS logic)
    // =======================================

    let tipsArea = document.getElementById("tipsArea");
    let tips = [];

    if (monthTotal > 300) {
        tips.push("📌 Your monthly usage is above 300 kWh. Consider using 5-star rated appliances.");
    }
    if (todayTotal > 15) {
        tips.push("📌 Today's usage is above 15 kWh. Switch off unused devices.");
    }
    if (avgDaily > 10) {
        tips.push("📌 Your daily average is above 10 kWh. Use LED lights and set AC to 24°C.");
    }
    if (changePercent > 20) {
        tips.push("📌 Usage spiked over 20% this month. Review your heavy appliances.");
    }
    if (tips.length === 0) {
        tips.push("✅ Great job! Your energy usage looks healthy. Keep it up!");
    }

    tips.forEach(function (tip) {
        let div = document.createElement("div");
        div.className = "tip-item";
        div.innerText = tip;
        tipsArea.appendChild(div);
    });

    // =======================================
    // 8. FORM VALIDATION (JS only)
    // =======================================

    // Set default date to today
    let dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(function (inp) {
        inp.value = todayStr;
    });
});

// --- Form validators ---
function validateUsageForm() {
    let date  = document.getElementById("usageDate");
    let units = document.getElementById("usageUnits");
    let rate  = document.getElementById("usageRate");
    let valid = true;

    if (!date.value) {
        document.getElementById("usageDateErr").innerText = "Please select a date";
        valid = false;
    } else {
        document.getElementById("usageDateErr").innerText = "";
    }

    if (!units.value || parseFloat(units.value) <= 0) {
        document.getElementById("usageUnitsErr").innerText = "Enter valid units";
        valid = false;
    } else {
        document.getElementById("usageUnitsErr").innerText = "";
    }

    if (!rate.value || parseFloat(rate.value) <= 0) {
        document.getElementById("usageRateErr").innerText = "Enter valid rate";
        valid = false;
    } else {
        document.getElementById("usageRateErr").innerText = "";
    }

    if (!valid) alert("Please correct all fields");
    return valid;
}

function validateApplianceForm() {
    let name    = document.getElementById("appName");
    let wattage = document.getElementById("appWattage");
    let hours   = document.getElementById("appHours");
    let date    = document.getElementById("appDate");
    let valid = true;

    if (!name.value || name.value.length < 2) {
        document.getElementById("appNameErr").innerText = "Enter appliance name";
        valid = false;
    } else {
        document.getElementById("appNameErr").innerText = "";
    }

    if (!wattage.value || parseFloat(wattage.value) <= 0) {
        document.getElementById("appWattageErr").innerText = "Enter valid wattage";
        valid = false;
    } else {
        document.getElementById("appWattageErr").innerText = "";
    }

    if (!hours.value || parseFloat(hours.value) <= 0) {
        document.getElementById("appHoursErr").innerText = "Enter valid hours";
        valid = false;
    } else {
        document.getElementById("appHoursErr").innerText = "";
    }

    if (!date.value) {
        document.getElementById("appDateErr").innerText = "Please select a date";
        valid = false;
    } else {
        document.getElementById("appDateErr").innerText = "";
    }

    if (!valid) alert("Please correct all fields");
    return valid;
}
