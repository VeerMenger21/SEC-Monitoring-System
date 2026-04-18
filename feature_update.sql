-- ============================================
-- Feature Updates — Run in phpMyAdmin
-- ============================================

-- 1. Add role column to users table
ALTER TABLE users ADD COLUMN role VARCHAR(10) NOT NULL DEFAULT 'user';

-- 2. Set an admin user (change 'admin' to your desired admin username)
-- UPDATE users SET role = 'admin' WHERE username = 'admin';

-- 3. Contact messages setup
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    name VARCHAR(255),
    email VARCHAR(255),
    subject VARCHAR(255),
    message TEXT,
    is_deleted TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 4. Add soft delete capabilities
ALTER TABLE users ADD COLUMN IF NOT EXISTS is_deleted TINYINT(1) DEFAULT 0;
ALTER TABLE energy_usage ADD COLUMN IF NOT EXISTS is_deleted TINYINT(1) DEFAULT 0;
ALTER TABLE appliance_usage ADD COLUMN IF NOT EXISTS is_deleted TINYINT(1) DEFAULT 0;
ALTER TABLE feedback ADD COLUMN IF NOT EXISTS is_deleted TINYINT(1) DEFAULT 0;
