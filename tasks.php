<?php
include "config/database.php"; // your DB connection

$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? 'all';

$sql = "SELECT * FROM tasks WHERE 1";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (title LIKE '%$search%' 
            OR pickup LIKE '%$search%' 
            OR delivery LIKE '%$search%')";
}

if ($status != 'all') {
    $status = $conn->real_escape_string($status);
    $sql .= " AND status = '$status'";
}

$sql .= " ORDER BY posted_at DESC";

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

    <!-- Search & Filter -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-8">
            <input type="text" 
                   name="search" 
                   value="<?php echo htmlspecialchars($search); ?>" 
                   class="form-control" 
                   placeholder="Search tasks by title or location...">
        </div>

        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="all" <?php if($status=='all') echo 'selected'; ?>>All Status</option>
                <option value="open" <?php if($status=='open') echo 'selected'; ?>>Open</option>
                <option value="in-progress" <?php if($status=='in-progress') echo 'selected'; ?>>In Progress</option>
                <option value="completed" <?php if($status=='completed') echo 'selected'; ?>>Completed</option>
            </select>
        </div>

        <div class="col-md-1">
            <button class="btn btn-dark w-100">Filter</button>
        </div>
    </form>

    <!-- Task Cards -->
    <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while($task = $result->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($task['title']); ?></h5>

                            <p class="mb-1">
                                <strong>Pickup:</strong> <?php echo htmlspecialchars($task['pickup']); ?>
                            </p>

                            <p class="mb-1">
                                <strong>Delivery:</strong> <?php echo htmlspecialchars($task['delivery']); ?>
                            </p>

                            <p class="mb-2">
                                <strong>Reward:</strong> <?php echo htmlspecialchars($task['reward']); ?>
                            </p>

                            <span class="badge bg-<?php 
                                echo $task['status'] == 'open' ? 'success' :
                                    ($task['status'] == 'in-progress' ? 'warning' : 'secondary');
                            ?>">
                                <?php echo ucfirst($task['status']); ?>
                            </span>

                            <?php if (!empty($task['rating'])): ?>
                                <p class="mt-2 text-muted">
                                    ⭐ Rating: <?php echo $task['rating']; ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5 text-muted">
                <h4>No tasks found</h4>
                <p>Try adjusting your search or filters</p>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php include "footer.php"; ?>

</body>
</html>