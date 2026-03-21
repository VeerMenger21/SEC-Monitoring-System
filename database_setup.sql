-- ============================================
-- Smart Energy Consumption Monitoring System
-- Run this file in phpMyAdmin to set up the DB
-- ============================================

CREATE DATABASE IF NOT EXISTS energy_system;
USE energy_system;

-- USERS TABLE
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    dob DATE,
    phone VARCHAR(15),
    address TEXT,
    city VARCHAR(50),
    zip VARCHAR(10),
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ENERGY USAGE TABLE
CREATE TABLE IF NOT EXISTS energy_usage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    date DATE NOT NULL,
    units_consumed DECIMAL(10,2) NOT NULL,
    rate_per_unit DECIMAL(10,2) NOT NULL DEFAULT 7.50,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- APPLIANCE USAGE TABLE
CREATE TABLE IF NOT EXISTS appliance_usage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    appliance_name VARCHAR(100) NOT NULL,
    wattage DECIMAL(10,2) NOT NULL,
    hours_used DECIMAL(5,2) NOT NULL,
    date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- FEEDBACK TABLE
CREATE TABLE IF NOT EXISTS feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    rating INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
