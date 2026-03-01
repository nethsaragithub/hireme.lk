<?php
/**
 * Task Management Utilities
 * 
 * Include this file in any PHP page where you want automatic task expiry checking
 * and task management functions.
 * 
 * Usage: require_once "includes/task-utils.php";
 */

// Run expiry check before displaying tasks (optional but recommended)
function checkAndExpireOldTasks($conn) {
    try {
        $expire_stmt = $conn->prepare("
            UPDATE tasks 
            SET status = 'cancelled', expired_at = NOW()
            WHERE status = 'open' 
              AND runner_id IS NULL
              AND TIMESTAMPDIFF(HOUR, created_at, NOW()) >= 24
              AND expired_at IS NULL
        ");
        
        if($expire_stmt->execute()) {
            $affected = $expire_stmt->affected_rows;
            $expire_stmt->close();
            return $affected;
        }
        $expire_stmt->close();
    } catch (Exception $e) {
        error_log("Task expiry error: " . $e->getMessage());
    }
    return 0;
}

// Get open tasks count
function getOpenTasksCount($conn) {
    try {
        $stmt = $conn->prepare("
            SELECT COUNT(*) as count 
            FROM tasks 
            WHERE status = 'open' 
              AND TIMESTAMPDIFF(HOUR, created_at, NOW()) < 24
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['count'];
    } catch (Exception $e) {
        return 0;
    }
}

// Get runner's active tasks count
function getRunnerActiveTasksCount($conn, $runner_id) {
    try {
        $stmt = $conn->prepare("
            SELECT COUNT(*) as count 
            FROM tasks 
            WHERE runner_id = ? 
              AND status = 'taken'
        ");
        $stmt->bind_param("i", $runner_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['count'];
    } catch (Exception $e) {
        return 0;
    }
}

// Get runner's completed tasks count
function getRunnerCompletedTasksCount($conn, $runner_id) {
    try {
        $stmt = $conn->prepare("
            SELECT COUNT(*) as count 
            FROM tasks 
            WHERE runner_id = ? 
              AND status = 'completed'
        ");
        $stmt->bind_param("i", $runner_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['count'];
    } catch (Exception $e) {
        return 0;
    }
}

// Get total earnings for a runner
function getRunnerTotalEarnings($conn, $runner_id) {
    try {
        $stmt = $conn->prepare("
            SELECT SUM(reward) as total 
            FROM tasks 
            WHERE runner_id = ? 
              AND status = 'completed'
        ");
        $stmt->bind_param("i", $runner_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['total'] ?? 0;
    } catch (Exception $e) {
        return 0;
    }
}

// Get task details by ID
function getTaskDetails($conn, $task_id) {
    try {
        $stmt = $conn->prepare("
            SELECT t.*, u.email as runner_email, u.phone as runner_phone
            FROM tasks t
            LEFT JOIN users u ON t.runner_id = u.id
            WHERE t.id = ?
        ");
        $stmt->bind_param("i", $task_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $task = $result->fetch_assoc();
        $stmt->close();
        return $task;
    } catch (Exception $e) {
        return null;
    }
}

// Check if task is still available
function isTaskAvailable($conn, $task_id) {
    try {
        $stmt = $conn->prepare("
            SELECT id 
            FROM tasks 
            WHERE id = ? 
              AND status = 'open' 
              AND runner_id IS NULL
              AND TIMESTAMPDIFF(HOUR, created_at, NOW()) < 24
        ");
        $stmt->bind_param("i", $task_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $available = $result->num_rows > 0;
        $stmt->close();
        return $available;
    } catch (Exception $e) {
        return false;
    }
}

// Get time remaining before task expires
function getTaskTimeRemaining($conn, $task_id) {
    try {
        $stmt = $conn->prepare("
            SELECT 
                TIMESTAMPDIFF(HOUR, created_at, NOW()) as hours_elapsed,
                24 - TIMESTAMPDIFF(HOUR, created_at, NOW()) as hours_remaining
            FROM tasks 
            WHERE id = ? AND status = 'open'
        ");
        $stmt->bind_param("i", $task_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    } catch (Exception $e) {
        return null;
    }
}

// Get recent activity for a task
function getTaskActivity($conn, $task_id, $limit = 10) {
    try {
        $stmt = $conn->prepare("
            SELECT 
                tal.id,
                tal.action,
                tal.description,
                tal.created_at,
                u.email as user_email
            FROM task_activity_log tal
            LEFT JOIN users u ON tal.runner_id = u.id
            WHERE tal.task_id = ?
            ORDER BY tal.created_at DESC
            LIMIT ?
        ");
        $stmt->bind_param("ii", $task_id, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $activities = [];
        while($row = $result->fetch_assoc()) {
            $activities[] = $row;
        }
        $stmt->close();
        return $activities;
    } catch (Exception $e) {
        return [];
    }
}

// Auto-call expiry check on every load (optional)
// Uncomment below to enable automatic expiry checking
// Note: This will run on every page load, which may impact performance
// For production, use a cron job instead

/* 
if(getenv('AUTO_CHECK_EXPIRY') === 'true') {
    checkAndExpireOldTasks($conn);
}
*/

?>
