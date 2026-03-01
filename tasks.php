<?php
session_start();
include "config/database.php"; // your DB connection

// Check if user has any session message
$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

// Build SQL - Show only OPEN tasks and filter out expired ones (> 24 hours old with no runner)
$sql = "SELECT id, title, description, category, pickup, delivery, reward, status, created_at,
                TIMESTAMPDIFF(HOUR, created_at, NOW()) AS hours_posted
        FROM tasks 
        WHERE status = 'open' 
          AND (expired_at IS NULL OR expired_at > NOW())
          AND TIMESTAMPDIFF(HOUR, created_at, NOW()) < 24";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (title LIKE '%$search%' 
            OR pickup LIKE '%$search%' 
            OR delivery LIKE '%$search%')";
}

if (!empty($category)) {
    $category = $conn->real_escape_string($category);
    $sql .= " AND category = '$category'";
}

$sql .= " ORDER BY created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Browse Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include "header.php"; ?>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Browse Tasks</h2>
            <p class="text-muted">Find tasks near you and start earning</p>
        </div>
        <a href="post-task.php" class="btn btn-primary">+ Post a Task</a>
    </div>

    <!-- Success/Error Messages -->
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Search & Filter -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-6">
            <input type="text" 
                   name="search" 
                   value="<?php echo htmlspecialchars($search); ?>" 
                   class="form-control" 
                   placeholder="Search tasks by title or location...">
        </div>

        <div class="col-md-4">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                <option value="documents" <?php if($category=='documents') echo 'selected'; ?>>Documents</option>
                <option value="parcels" <?php if($category=='parcels') echo 'selected'; ?>>Parcels</option>
                <option value="medicine" <?php if($category=='medicine') echo 'selected'; ?>>Medicine</option>
                <option value="electronics" <?php if($category=='electronics') echo 'selected'; ?>>Electronics</option>
                <option value="other" <?php if($category=='other') echo 'selected'; ?>>Other</option>
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-dark w-100">Filter</button>
        </div>
    </form>

    <!-- Task Cards -->
    <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while($task = $result->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card shadow-sm h-100 d-flex flex-column">
                        <div class="card-body flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title"><?php echo htmlspecialchars($task['title']); ?></h5>
                                <span class="badge bg-success">Open</span>
                            </div>

                            <p class="mb-1">
                                <strong>Category:</strong> 
                                <span class="badge bg-secondary"><?php echo htmlspecialchars($task['category']); ?></span>
                            </p>

                            <p class="mb-1 small">
                                <strong>📍 Pickup:</strong> <?php echo htmlspecialchars($task['pickup']); ?>
                            </p>

                            <p class="mb-2 small">
                                <strong>📍 Delivery:</strong> <?php echo htmlspecialchars($task['delivery']); ?>
                            </p>

                            <p class="mb-2">
                                <strong>Description:</strong><br>
                                <small><?php echo htmlspecialchars(substr($task['description'], 0, 80)); ?>...</small>
                            </p>

                            <p class="text-muted small mb-2">
                                Posted: <?php echo date('M d, Y H:i', strtotime($task['created_at'])); ?>
                                <br>
                                (<?php echo $task['hours_posted'] < 1 ? 'Just now' : $task['hours_posted'] . ' hours ago'; ?>)
                            </p>
                        </div>

                        <div class="card-footer bg-white pt-3">
                            <p class="mb-3 fs-5 text-success fw-bold">
                                💰 Rs. <?php echo number_format($task['reward']); ?>
                            </p>

                            <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'runner'): ?>
                                <a href="pick-task.php?task_id=<?php echo $task['id']; ?>" class="btn btn-success w-100 btn-sm">
                                    Accept This Task →
                                </a>
                            <?php elseif(isset($_SESSION['role']) && $_SESSION['role'] === 'customer'): ?>
                                <button class="btn btn-outline-secondary w-100 btn-sm" disabled>
                                    View My Tasks
                                </button>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-primary w-100 btn-sm">
                                    Login to Pick Task
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5 text-muted">
                <h4>No Open Tasks Available</h4>
                <p>Try adjusting your search or filters. Check back soon!</p>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php include "footer.php"; ?>

</body>
</html>