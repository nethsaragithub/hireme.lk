-- ========================================================
-- HIREME.LK - COMPLETE DATABASE SCHEMA
-- Version: 2.0 with Task System Integration
-- Date: March 2, 2026
-- ========================================================

-- ========================================================
-- DROP EXISTING DATABASE & CREATE FRESH
-- ========================================================
DROP DATABASE IF EXISTS hireme;
CREATE DATABASE hireme CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE hireme;

-- ========================================================
-- TABLE 1: USERS
-- Description: Stores user accounts (customers, runners, admins)
-- ========================================================
CREATE TABLE users (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(150) NOT NULL UNIQUE,
  phone VARCHAR(20) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('customer', 'runner', 'admin') NOT NULL DEFAULT 'customer',
  profile_image VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  KEY idx_email (email),
  KEY idx_role (role),
  KEY idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci 
COMMENT='User accounts for customers, runners, and admins';

-- ========================================================
-- TABLE 2: TASKS
-- Description: Main tasks/delivery jobs table
-- Status: open (available), taken (assigned), completed, cancelled (expired/rejected)
-- ========================================================
CREATE TABLE tasks (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  runner_id INT DEFAULT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  category VARCHAR(100) DEFAULT NULL,
  pickup VARCHAR(255) NOT NULL,
  delivery VARCHAR(255) NOT NULL,
  reward VARCHAR(50) NOT NULL,
  status ENUM('open', 'taken', 'cancelled', 'completed') DEFAULT 'open',
  
  -- Timestamps for lifecycle tracking
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  taken_at TIMESTAMP NULL DEFAULT NULL,
  completed_at TIMESTAMP NULL DEFAULT NULL,
  expired_at TIMESTAMP NULL DEFAULT NULL,
  posted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  
  -- Additional fields
  rating DECIMAL(2,1) DEFAULT NULL,
  
  -- Foreign Keys
  CONSTRAINT fk_tasks_runner FOREIGN KEY (runner_id) 
    REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
  
  -- Indexes for Performance
  KEY idx_status (status),
  KEY idx_runner_id (runner_id),
  KEY idx_created_at (created_at),
  KEY idx_taken_at (taken_at),
  KEY idx_open_tasks (status, created_at),
  KEY idx_category (category),
  KEY idx_pickup (pickup),
  KEY idx_delivery (delivery)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Delivery tasks/jobs with full lifecycle tracking';

-- ========================================================
-- TABLE 3: TASK_ACTIVITY_LOG
-- Description: Audit trail for all task status changes
-- Used for: Dispute resolution, analytics, compliance
-- ========================================================
CREATE TABLE task_activity_log (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  task_id INT NOT NULL,
  runner_id INT DEFAULT NULL,
  action VARCHAR(50) NOT NULL COMMENT 'open, taken, cancelled, completed, etc',
  description TEXT,
  ip_address VARCHAR(45) DEFAULT NULL,
  user_agent VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  -- Foreign Keys
  CONSTRAINT fk_activity_task FOREIGN KEY (task_id) 
    REFERENCES tasks(id) ON DELETE CASCADE,
  CONSTRAINT fk_activity_runner FOREIGN KEY (runner_id) 
    REFERENCES users(id) ON DELETE SET NULL,
  
  -- Indexes
  KEY idx_task_id (task_id),
  KEY idx_action (action),
  KEY idx_runner_id (runner_id),
  KEY idx_created_at (created_at),
  KEY idx_task_action (task_id, action)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Audit trail for task changes and history';

-- ========================================================
-- TABLE 4: SUPPORT_MESSAGES
-- Description: Customer support/contact form submissions
-- ========================================================
CREATE TABLE support_messages (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  email VARCHAR(255),
  message TEXT,
  status ENUM('new', 'read', 'resolved') DEFAULT 'new',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  resolved_at DATETIME DEFAULT NULL,
  
  -- Indexes
  KEY idx_email (email),
  KEY idx_status (status),
  KEY idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Customer support messages and inquiries';

-- ========================================================
-- TABLE 5: RUNNER_STATS (Optional - for analytics)
-- Description: Cached statistics for runner performance
-- ========================================================
CREATE TABLE runner_stats (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  runner_id INT NOT NULL UNIQUE,
  total_tasks_completed INT DEFAULT 0,
  total_earnings DECIMAL(10,2) DEFAULT 0,
  average_rating DECIMAL(2,1) DEFAULT NULL,
  last_task_completed DATETIME DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  -- Foreign Key
  CONSTRAINT fk_runner_stats FOREIGN KEY (runner_id) 
    REFERENCES users(id) ON DELETE CASCADE,
  
  -- Index
  KEY idx_earnings (total_earnings)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
COMMENT='Cached runner performance statistics';

-- ========================================================
-- VIEWS FOR COMMON QUERIES
-- ========================================================

-- View 1: Available/Open Tasks
CREATE OR REPLACE VIEW open_tasks_view AS
SELECT 
    t.id,
    t.title,
    t.description,
    t.category,
    t.pickup,
    t.delivery,
    t.reward,
    t.status,
    t.created_at,
    TIMESTAMPDIFF(HOUR, t.created_at, NOW()) AS hours_since_posted,
    (24 - TIMESTAMPDIFF(HOUR, t.created_at, NOW())) AS hours_remaining
FROM tasks t
WHERE t.status = 'open' 
  AND (t.expired_at IS NULL OR t.expired_at > NOW())
  AND TIMESTAMPDIFF(HOUR, t.created_at, NOW()) < 24
ORDER BY t.created_at DESC;

-- View 2: Tasks Taken by Runners
CREATE OR REPLACE VIEW taken_tasks_view AS
SELECT 
    t.id,
    t.title,
    t.description,
    t.category,
    t.pickup,
    t.delivery,
    t.reward,
    t.runner_id,
    u.email AS runner_email,
    u.phone AS runner_phone,
    u.profile_image AS runner_profile,
    t.status,
    t.created_at,
    t.taken_at,
    TIMESTAMPDIFF(HOUR, t.taken_at, NOW()) AS hours_since_taken
FROM tasks t
LEFT JOIN users u ON t.runner_id = u.id
WHERE t.status = 'taken'
ORDER BY t.taken_at DESC;

-- View 3: Completed Tasks with Runner Info
CREATE OR REPLACE VIEW completed_tasks_view AS
SELECT 
    t.id,
    t.title,
    t.category,
    t.pickup,
    t.delivery,
    t.reward,
    t.rating,
    t.runner_id,
    u.email AS runner_email,
    u.phone AS runner_phone,
    t.created_at,
    t.taken_at,
    t.completed_at,
    TIMESTAMPDIFF(MINUTE, t.taken_at, t.completed_at) AS delivery_time_minutes
FROM tasks t
LEFT JOIN users u ON t.runner_id = u.id
WHERE t.status = 'completed'
ORDER BY t.completed_at DESC;

-- View 4: Runner Summary (Daily Active Tasks)
CREATE OR REPLACE VIEW runner_active_tasks_view AS
SELECT 
    u.id AS runner_id,
    u.email,
    u.phone,
    COUNT(CASE WHEN t.status = 'taken' THEN 1 END) AS active_tasks,
    COUNT(CASE WHEN t.status = 'completed' AND DATE(t.completed_at) = CURDATE() THEN 1 END) AS completed_today,
    SUM(CASE WHEN t.status = 'completed' THEN CAST(t.reward AS DECIMAL(10,2)) ELSE 0 END) AS total_earnings
FROM users u
LEFT JOIN tasks t ON u.id = t.runner_id
WHERE u.role = 'runner'
GROUP BY u.id, u.email, u.phone;

-- ========================================================
-- STORED PROCEDURES (Optional - for common operations)
-- ========================================================

-- Procedure: Expire old open tasks
DELIMITER $$

CREATE PROCEDURE sp_expire_old_tasks()
BEGIN
  DECLARE affected_count INT;
  
  -- Update tasks to cancelled if > 24 hours old and still open
  UPDATE tasks 
  SET status = 'cancelled', expired_at = NOW()
  WHERE status = 'open' 
    AND runner_id IS NULL
    AND TIMESTAMPDIFF(HOUR, created_at, NOW()) >= 24
    AND expired_at IS NULL;
  
  SET affected_count = ROW_COUNT();
  
  -- Log the expiration
  INSERT INTO task_activity_log (task_id, action, description)
  SELECT id, 'cancelled', 'Expired - not taken within 24 hours'
  FROM tasks 
  WHERE status = 'cancelled' 
    AND expired_at = NOW()
    AND runner_id IS NULL
  LIMIT affected_count;
END$$

DELIMITER ;

-- ========================================================
-- SAMPLE DATA (Optional - for testing)
-- ========================================================

-- Insert sample users for testing
INSERT INTO users (email, phone, password, role, profile_image) VALUES
('admin@hireme.lk', '0700000000', '$2y$10$ooG7eIb/VH6FQzU5W/3E8uaHqTZGLVGVLhR/5Q/bBVzYb1h/vwWlS', 'admin', NULL),
('customer1@hireme.lk', '0712345678', '$2y$10$ooG7eIb/VH6FQzU5W/3E8uaHqTZGLVGVLhR/5Q/bBVzYb1h/vwWlS', 'customer', NULL),
('runner1@hireme.lk', '0723456789', '$2y$10$ooG7eIb/VH6FQzU5W/3E8uaHqTZGLVGVLhR/5Q/bBVzYb1h/vwWlS', 'runner', NULL),
('runner2@hireme.lk', '0734567890', '$2y$10$ooG7eIb/VH6FQzU5W/3E8uaHqTZGLVGVLhR/5Q/bBVzYb1h/vwWlS', 'runner', NULL);

-- Insert sample tasks
INSERT INTO tasks (title, description, category, pickup, delivery, reward, status, created_at, taken_at, completed_at, expired_at) VALUES
('Pick documents from office', 'Important corporate documents that need delivery', 'documents', 'Colombo 7 - Office', 'Colombo 3 - Home', '500', 'open', NOW(), NULL, NULL, NULL),
('Deliver medicine bottle', 'Urgent medicine delivery to patient', 'medicine', 'Colombo Center', 'Negombo', '300', 'open', DATE_SUB(NOW(), INTERVAL 5 HOUR), NULL, NULL, NULL),
('Transport electronics', 'Deliver laptop and accessories safely', 'electronics', 'Kandy', 'Colombo 7', '1000', 'open', DATE_SUB(NOW(), INTERVAL 2 HOUR), NULL, NULL, NULL),
('Package delivery', 'Food parcels delivery to office', 'parcels', 'Colombo 5', 'Colombo 6', '250', 'taken', DATE_SUB(NOW(), INTERVAL 1 HOUR), DATE_SUB(NOW(), INTERVAL 30 MINUTE), NULL, NULL),
('Urgent documents', 'Time-sensitive legal documents', 'documents', 'Colombo 1', 'Mount Lavinia', '800', 'completed', DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY), NULL);

-- ========================================================
-- SUMMARY
-- ========================================================
-- ✓ 5 Main Tables (users, tasks, task_activity_log, support_messages, runner_stats)
-- ✓ 15+ Performance Indexes
-- ✓ 4 Useful Views
-- ✓ Foreign Key Constraints
-- ✓ 1 Stored Procedure
-- ✓ Sample Data for Testing
-- ✓ Full Task Lifecycle Support
-- ✓ Audit Trail System
-- ✓ Ready for Production
-- ========================================================
