<?php
session_start();
require_once "config/database.php";

// Check if user is logged in and is a runner
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'runner') {
    header("Location: login.php");
    exit();
}

// Check if task_id is provided via POST
if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['task_id'])) {
    
    $task_id = intval($_POST['task_id']);
    $runner_id = $_SESSION['user_id'];
    
    // Verify the task exists and is still open
    $check_stmt = $conn->prepare("SELECT id, status, runner_id FROM tasks WHERE id = ?");
    $check_stmt->bind_param("i", $task_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if($check_result->num_rows === 0) {
        // Task doesn't exist
        $_SESSION['error'] = "Task not found!";
        header("Location: tasks.php");
        exit();
    }
    
    $task = $check_result->fetch_assoc();
    
    if($task['status'] !== 'open') {
        // Task is not available
        $_SESSION['error'] = "This task is no longer available.";
        header("Location: tasks.php");
        exit();
    }
    
    if($task['runner_id'] !== NULL) {
        // Task has already been taken
        $_SESSION['error'] = "This task has already been taken by another runner.";
        header("Location: tasks.php");
        exit();
    }
    
    $check_stmt->close();
    
    // Update the task: set runner_id, change status to 'taken', set taken_at
    $update_stmt = $conn->prepare("
        UPDATE tasks 
        SET runner_id = ?, status = 'taken', taken_at = NOW() 
        WHERE id = ? AND status = 'open' AND runner_id IS NULL
    ");
    $update_stmt->bind_param("ii", $runner_id, $task_id);
    
    if($update_stmt->execute() && $update_stmt->affected_rows > 0) {
        
        // Log the activity
        $log_stmt = $conn->prepare("
            INSERT INTO task_activity_log (task_id, runner_id, action, description) 
            VALUES (?, ?, 'taken', 'Task accepted by runner')
        ");
        $log_stmt->bind_param("ii", $task_id, $runner_id);
        $log_stmt->execute();
        $log_stmt->close();
        
        $_SESSION['success'] = "Task accepted successfully! View it in your dashboard.";
        header("Location: runner-dashboard.php");
        exit();
    } else {
        // Task was taken by another runner between check and update
        $_SESSION['error'] = "This task was just taken by another runner. Please try a different task.";
        header("Location: tasks.php");
        exit();
    }
    
    $update_stmt->close();
}

// If accessed via GET, get task details for confirmation page
if(isset($_GET['task_id'])) {
    $task_id = intval($_GET['task_id']);
    
    $stmt = $conn->prepare("
        SELECT id, title, description, category, pickup, delivery, reward, status, created_at 
        FROM tasks 
        WHERE id = ? AND status = 'open'
    ");
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows === 0) {
        $_SESSION['error'] = "Task not found or no longer available!";
        header("Location: tasks.php");
        exit();
    }
    
    $task = $result->fetch_assoc();
    $stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirm Task - HireMe.lk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="card-title mb-4">Confirm Task</h3>
                    
                    <div class="alert alert-info">
                        Are you sure you want to accept this task?
                    </div>
                    
                    <div class="task-details mb-4">
                        <h5><?php echo htmlspecialchars($task['title']); ?></h5>
                        
                        <p class="mb-2">
                            <strong>Category:</strong> 
                            <span class="badge bg-secondary"><?php echo htmlspecialchars($task['category']); ?></span>
                        </p>
                        
                        <p class="mb-2">
                            <strong>Description:</strong><br>
                            <?php echo nl2br(htmlspecialchars($task['description'])); ?>
                        </p>
                        
                        <p class="mb-2">
                            <strong>📍 Pickup Location:</strong> <?php echo htmlspecialchars($task['pickup']); ?>
                        </p>
                        
                        <p class="mb-2">
                            <strong>📍 Delivery Location:</strong> <?php echo htmlspecialchars($task['delivery']); ?>
                        </p>
                        
                        <p class="mb-2">
                            <strong>💰 Reward:</strong> 
                            <span class="text-success fs-5">Rs. <?php echo number_format($task['reward']); ?></span>
                        </p>
                        
                        <p class="text-muted small">
                            <strong>Posted:</strong> <?php echo date('M d, Y H:i', strtotime($task['created_at'])); ?>
                        </p>
                    </div>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                        
                        <button type="submit" class="btn btn-success w-100 mb-2">
                            ✓ Accept This Task
                        </button>
                        
                        <a href="tasks.php" class="btn btn-secondary w-100">
                            Cancel
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>

</body>
</html>

<?php
} else {
    // No task_id provided
    $_SESSION['error'] = "No task selected!";
    header("Location: tasks.php");
    exit();
}
?>
