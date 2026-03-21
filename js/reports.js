/* ============================================
   Reports Page JS — ALL calculations here
   PHP only provides raw usageData
   ============================================ */

document.addEventListener("DOMContentLoaded", function () {

    const today = new Date();
    const currentMonth = today.getMonth() + 1;
    const currentYear = today.getFullYear();

    // =====================
    // 1. AGGREGATE BY MONTH
    // =====================
    let monthlyMap = {}; // "2026-03" => { units: 0, rateSum: 0, count: 0 }

    let totalAll = 0;
    let thisMonthTotal = 0;
    let lastMonthTotal = 0;
    let latestRate = 7.5;

    usageData.forEach(function (row) {
        let d = new Date(row.date);
        let units = parseFloat(row.units_consumed);
        let rate = parseFloat(row.rate_per_unit);
        let monthKey = row.date.substring(0, 7); // "YYYY-MM"

        totalAll += units;

        // This month
        if (d.getMonth() + 1 === currentMonth && d.getFullYear() === currentYear) {
            thisMonthTotal += units;
            latestRate = rate;
        }

        // Last month
        let lastM = currentMonth === 1 ? 12 : currentMonth - 1;
        let lastY = currentMonth === 1 ? currentYear - 1 : currentYear;
        if (d.getMonth() + 1 === lastM && d.getFullYear() === lastY) {
            lastMonthTotal += units;
        }

        // Monthly aggregation
        if (!monthlyMap[monthKey]) {
            monthlyMap[monthKey] = { units: 0, rateSum: 0, count: 0 };
        }
        monthlyMap[monthKey].units += units;
        monthlyMap[monthKey].rateSum += rate;
        monthlyMap[monthKey].count += 1;
    });

    // =====================
    // 2. UPDATE CARDS
    // =====================
    let bill = (thisMonthTotal * latestRate).toFixed(2);

    document.getElementById("totalAll").innerText = totalAll.toFixed(2) + " kWh";
    document.getElementById("thisMonth").innerText = thisMonthTotal.toFixed(2) + " kWh";
    document.getElementById("billEst").innerText = "₹" + bill;

    let change = 0;
    let changeEl = document.getElementById("monthChange");
    if (lastMonthTotal > 0) {
        change = (((thisMonthTotal - lastMonthTotal) / lastMonthTotal) * 100).toFixed(1);
    }

    if (change > 0) {
        changeEl.innerText = "▲ " + Math.abs(change) + "%";
        changeEl.classList.add("status-red");
    } else if (change < 0) {
        changeEl.innerText = "▼ " + Math.abs(change) + "%";
        changeEl.classList.add("status-green");
    } else {
        changeEl.innerText = "— 0%";
    }

    // =====================
    // 3. MONTHLY TABLE
    // =====================
    let sortedMonths = Object.keys(monthlyMap).sort().reverse();
    let tbody = document.getElementById("monthlyTableBody");

    if (sortedMonths.length > 0) {
        tbody.innerHTML = "";
        sortedMonths.forEach(function (month) {
            let data = monthlyMap[month];
            let avgRate = (data.rateSum / data.count).toFixed(2);
            let estBill = (data.units * avgRate).toFixed(2);

            let tr = document.createElement("tr");
            tr.innerHTML =
                "<td>" + month + "</td>" +
                "<td>" + data.units.toFixed(2) + "</td>" +
                "<td>₹" + avgRate + "</td>" +
                "<td>₹" + estBill + "</td>";
            tbody.appendChild(tr);
        });
    }

    // =====================
    // 4. MONTHLY TREND CHART
    // =====================
    let chartMonths = Object.keys(monthlyMap).sort();
    let chartValues = chartMonths.map(function (m) { return monthlyMap[m].units; });

    let ctx = document.getElementById("monthlyChart").getContext("2d");
    new Chart(ctx, {
        type: "line",
        data: {
            labels: chartMonths,
            datasets: [{
                label: "kWh Used",
                data: chartValues,
                borderColor: "#1e3799",
                backgroundColor: "rgba(30,55,153,0.1)",
                fill: true,
                tension: 0.3,
                pointRadius: 5,
                pointBackgroundColor: "#1e3799"
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
});
