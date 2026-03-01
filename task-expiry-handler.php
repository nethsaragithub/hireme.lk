<?php
/**
 * Task Expiry Handler
 * 
 * This script handles task expiration logic:
 * - Marks tasks as expired if they're open and older than 24 hours
 * - Can be called via:
 *   1. Cron job: curl http://yoursite.com/task-expiry-handler.php
 *   2. Scheduled task runner
 *   3. Called from other PHP files during task browsing
 * 
 * Security: Add a simple token check to prevent unauthorized calls
 */

require_once "config/database.php";

// Simple security token (Optional - add to .env in production)
$EXPIRY_TOKEN = "your-secret-expiry-token-change-this";

// Allow these types of calls:
// 1. From cron job with token
// 2. From internal PHP files (check with $_SERVER['REMOTE_ADDR'])
// 3. Command line execution

$token = $_GET['token'] ?? $_SERVER['HTTP_EXPIRY_TOKEN'] ?? '';
$is_cli = php_sapi_name() === 'cli';
$is_internal = ($_SERVER['REMOTE_ADDR'] ?? '') === '127.0.0.1' || ($_SERVER['REMOTE_ADDR'] ?? '') === 'localhost';

if(!$is_cli && !$is_internal && $token !== $EXPIRY_TOKEN) {
    die("Unauthorized access!");
}

// Start expiry process
$expired_count = 0;
$error_message = '';

try {
    // 1. Mark open tasks as expired if they're older than 24 hours
    $expire_stmt = $conn->prepare("
        UPDATE tasks 
        SET status = 'cancelled', expired_at = NOW()
        WHERE status = 'open' 
          AND runner_id IS NULL
          AND TIMESTAMPDIFF(HOUR, created_at, NOW()) >= 24
          AND expired_at IS NULL
    ");
    
    if($expire_stmt->execute()) {
        $expired_count = $expire_stmt->affected_rows;
        
        // Log the expiry action
        if($expired_count > 0) {
            $log_stmt = $conn->prepare("
                INSERT INTO task_activity_log (task_id, action, description) 
                SELECT id, 'cancelled', 'Task expired - not taken within 24 hours'
                FROM tasks 
                WHERE status = 'cancelled' 
                  AND expired_at = NOW()
                  AND runner_id IS NULL
                LIMIT ?
            ");
            $log_stmt->bind_param("i", $expired_count);
            $log_stmt->execute();
            $log_stmt->close();
        }
        
        $message = "Task Expiry Handler: $expired_count tasks marked as expired.";
        
    } else {
        $error_message = "Database error: " . $conn->error;
        $message = "Error: " . $error_message;
    }
    
    $expire_stmt->close();
    
} catch (Exception $e) {
    $error_message = $e->getMessage();
    $message = "Exception: " . $error_message;
}

$conn->close();

// Output result (useful for cron logs)
if($is_cli) {
    echo $message . "\n";
    exit($error_message ? 1 : 0);
} else {
    // For HTTP requests, return JSON
    header('Content-Type: application/json');
    echo json_encode([
        'success' => !$error_message,
        'message' => $message,
        'expired_count' => $expired_count,
        'error' => $error_message
    ]);
}
?>
