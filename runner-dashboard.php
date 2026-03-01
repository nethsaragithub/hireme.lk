<?php
session_start();
require_once "config/database.php"; // adjust path if necessary

// Check if user is logged in and is a runner
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'runner') {
    header("Location: login.php");
    exit();
}

$runnerId = $_SESSION['user_id'];

// Handle task status update (Mark as completed/cancelled)
if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['task_id']) && isset($_POST['action'])) {
    $task_id = intval($_POST['task_id']);
    $action = $_POST['action'];
    
    // Validate action
    if(!in_array($action, ['completed', 'cancelled'])) {
        $_SESSION['error'] = "Invalid action!";
        header("Location: runner-dashboard.php");
        exit();
    }
    
    // Verify the task belongs to this runner
    $verify_stmt = $conn->prepare("SELECT id FROM tasks WHERE id = ? AND runner_id = ?");
    $verify_stmt->bind_param("ii", $task_id, $runnerId);
    $verify_stmt->execute();
    
    if($verify_stmt->get_result()->num_rows === 0) {
        $_SESSION['error'] = "Task not found!";
        header("Location: runner-dashboard.php");
        exit();
    }
    $verify_stmt->close();
    
    // Update task status
    $update_stmt = $conn->prepare("UPDATE tasks SET status = ? WHERE id = ? AND runner_id = ?");
    $update_stmt->bind_param("sii", $action, $task_id, $runnerId);
    
    if($update_stmt->execute()) {
        // Log the activity
        $log_stmt = $conn->prepare("
            INSERT INTO task_activity_log (task_id, runner_id, action, description) 
            VALUES (?, ?, ?, ?)
        ");
        $desc = "Task marked as " . $action . " by runner";
        $log_stmt->bind_param("iiss", $task_id, $runnerId, $action, $desc);
        $log_stmt->execute();
        $log_stmt->close();
        
        $_SESSION['success'] = "Task updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update task!";
    }
    $update_stmt->close();
    
    header("Location: runner-dashboard.php");
    exit();
}

// Clear session messages
$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);

// Fetch tasks assigned to this runner (taken, completed, cancelled)
$stmt = $conn->prepare("
    SELECT id, title, description, pickup, delivery, reward, status, created_at, taken_at 
    FROM tasks 
    WHERE runner_id = ? 
    ORDER BY taken_at DESC
");
$stmt->bind_param("i", $runnerId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Runner Dashboard - HireMe.lk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<!-- Navbar -->
<nav class="bg-blue-500 text-white p-4 flex justify-between items-center">
    <div class="font-bold text-xl">HireMe.lk Runner Dashboard</div>
    <div>
        <span class="mr-4">Hello, <?php echo htmlspecialchars($_SESSION['email'] ?? 'Runner'); ?></span>
        <a href="logout.php" class="bg-white text-blue-500 px-3 py-1 rounded hover:bg-gray-200">Logout</a>
    </div>
</nav>

<!-- Main Content -->
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Your Dashboard</h1>
        <a href="tasks.php" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Browse More Tasks
        </a>
    </div>

    <!-- Success/Error Messages -->
    <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 p-4 mb-4 rounded">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="bg-green-100 text-green-700 p-4 mb-4 rounded">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <!-- Tab Navigation -->
    <div class="mb-6">
        <div class="flex gap-2">
            <button onclick="filterTasks('all')" id="tab-all" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                All Tasks (<?php echo $result->num_rows; ?>)
            </button>
            <button onclick="filterTasks('taken')" id="tab-taken" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                Active
            </button>
            <button onclick="filterTasks('completed')" id="tab-completed" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                Completed
            </button>
        </div>
    </div>

    <?php if($result->num_rows > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php while($task = $result->fetch_assoc()): ?>
                <div class="task-card bg-white p-4 rounded-lg shadow" data-status="<?php echo $task['status']; ?>">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="text-lg font-bold"><?php echo htmlspecialchars($task['title']); ?></h3>
                        <span class="px-3 py-1 rounded text-white text-sm font-semibold
                            <?php echo $task['status'] === 'taken' ? 'bg-yellow-500' : ($task['status'] === 'completed' ? 'bg-green-500' : 'bg-gray-500'); ?>">
                            <?php echo ucfirst($task['status']); ?>
                        </span>
                    </div>

                    <p class="text-gray-600 mb-2">
                        <strong>Description:</strong> <?php echo htmlspecialchars(substr($task['description'], 0, 100)); ?>...
                    </p>

                    <p class="text-sm mb-2">
                        <strong>📍 Pickup:</strong> <?php echo htmlspecialchars($task['pickup']); ?>
                    </p>

                    <p class="text-sm mb-2">
                        <strong>📍 Delivery:</strong> <?php echo htmlspecialchars($task['delivery']); ?>
                    </p>

                    <p class="text-sm mb-3 text-gray-500">
                        <strong>Accepted:</strong> <?php echo date('M d, Y H:i', strtotime($task['taken_at'])); ?>
                    </p>

                    <div class="border-t pt-3 mb-3">
                        <p class="text-2xl font-bold text-green-600">Rs. <?php echo number_format($task['reward']); ?></p>
                    </div>

                    <?php if($task['status'] === 'taken'): ?>
                        <form method="POST" class="flex gap-2">
                            <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                            <button type="submit" name="action" value="completed" class="flex-1 bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600 text-sm">
                                ✓ Mark Completed
                            </button>
                            <button type="submit" name="action" value="cancelled" class="flex-1 bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 text-sm">
                                ✗ Cancel
                            </button>
                        </form>
                    <?php elseif($task['status'] === 'completed'): ?>
                        <div class="bg-green-100 text-green-700 p-3 rounded text-center text-sm font-semibold">
                            ✓ Task Completed Successfully
                        </div>
                    <?php else: ?>
                        <div class="bg-gray-100 text-gray-700 p-3 rounded text-center text-sm font-semibold">
                            Task Cancelled
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="bg-gray-100 p-8 rounded-lg text-center">
            <p class="text-gray-700 text-lg mb-4">You haven't accepted any tasks yet.</p>
            <a href="tasks.php" class="bg-blue-500 text-white px-6 py-3 rounded hover:bg-blue-600 inline-block">
                Browse Available Tasks →
            </a>
        </div>
    <?php endif; ?>
</div>

<script>
function filterTasks(status) {
    const cards = document.querySelectorAll('.task-card');
    
    cards.forEach(card => {
        if(status === 'all' || card.dataset.status === status) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });

    // Update active tab
    document.querySelectorAll('[id^="tab-"]').forEach(btn => btn.classList.add('bg-gray-300', 'text-gray-700'));
    document.querySelectorAll('[id^="tab-"]').forEach(btn => btn.classList.remove('bg-blue-500', 'text-white'));
    document.getElementById('tab-' + status).classList.add('bg-blue-500', 'text-white');
    document.getElementById('tab-' + status).classList.remove('bg-gray-300', 'text-gray-700');
}
</script>

</body>
</html>