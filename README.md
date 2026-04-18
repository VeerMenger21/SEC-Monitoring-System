# WPL_Smart-Energy-Consumption-Monitoring-System_C1

Smart Energy Consumption Monitoring System is a web-based application with frontend and backend integration to monitor and analyze energy usage. It includes secure login, home dashboard, detailed reports, contact support and much more. The system ensures real-time data processing and efficient energy management.

## Technologies Used

- HTML, CSS, JavaScript (Vanilla)
- PHP (MySQL interaction only)
- MySQL Database
- Chart.js (for analytics charts)

## Features

- ✅ User Registration & Login (with password hashing)
- ✅ Real-Time Energy Monitoring Dashboard
- ✅ Energy Analytics (Bar & Doughnut Charts)
- ✅ Smart Alerts (usage warnings)
- ✅ Energy Saving Tips
- ✅ Appliance Tracking
- ✅ Bill Estimation
- ✅ Monthly Reports with Print Option
- ✅ CSV Data Export
- ✅ Admin Panel (Role-Based Access)
- ✅ Feedback System (Star Rating)
- ✅ Dark Mode Toggle
- ✅ Delete Usage Entries (CRUD)
- ✅ Soft Deletion (Data Preservation)
- ✅ Contact Support Page
- ✅ Cookies & Session Management

## Project Structure

```
/energy_project
├── index.php              → Home / Landing Page
├── auth.php               → Login & Registration
├── dashboard.php          → Main Dashboard
├── reports.php            → Monthly Reports
├── feedback.php           → Feedback Form
├── contact.php            → Contact Support Page
├── admin.php              → Admin Panel
├── config.php             → Database Connection
├── database_setup.sql     → Initial DB Schema
├── feature_update.sql     → Role Column Update
├── /css/style.css         → Main Stylesheet
├── /js/
│   ├── auth.js            → Form Validation
│   ├── dashboard.js       → Dashboard Logic & Charts
│   ├── reports.js         → Report Calculations
│   ├── feedback.js        → Feedback Validation
│   └── darkmode.js        → Dark Mode Toggle
├── /php/
│   ├── login.php          → Login Handler
│   ├── register.php       → Registration Handler
│   ├── auth_check.php     → Session Guard
│   ├── admin_check.php    → Admin Guard
│   ├── logout.php         → Logout
│   ├── save_usage.php     → Save Energy Usage
│   ├── save_appliance.php → Save Appliance Data
│   ├── save_feedback.php  → Save Feedback
│   ├── save_contact.php   → Save Contact Message
│   ├── export_csv.php     → CSV Export
│   ├── delete_usage.php   → Delete Usage Entry (Soft Delete)
│   └── delete_user.php    → Delete User (Soft Delete)
└── /assets/
    └── energy.jpg         → Hero Image
```

## How to Run

1. Install **XAMPP** and start Apache + MySQL
2. Create a database named `energy_system` in phpMyAdmin
3. Import `database_setup.sql` into the database
4. Run `feature_update.sql` to add the role column
5. Place the project folder in `C:\xampp\htdocs\`
6. Open `http://localhost/energy_project/` in your browser

## Admin Access

Set a user as admin in phpMyAdmin:

```sql
UPDATE users SET role = 'admin' WHERE username = 'your_username';
```
