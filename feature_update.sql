-- ============================================
-- Feature Updates — Run in phpMyAdmin
-- ============================================

-- 1. Add role column to users table
ALTER TABLE users ADD COLUMN role VARCHAR(10) NOT NULL DEFAULT 'user';

-- 2. Set an admin user (change 'admin' to your desired admin username)
-- UPDATE users SET role = 'admin' WHERE username = 'admin';
