<?php
session_start();
require_once "config/database.php"; // adjust path if necessary

// Check if user is logged in and is a runner
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'runner') {
    header("Location: login.php");
    exit();
}

$runnerId = $_SESSION['user_id'];

// Fetch tasks assigned to this runner
$stmt = $conn->prepare("SELECT id, title, description, pickup, delivery, reward, status, created_at FROM tasks WHERE runner_id = ?");
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
        <span class="mr-4">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a href="logout.php" class="bg-white text-blue-500 px-3 py-1 rounded hover:bg-gray-200">Logout</a>
    </div>
</nav>

<!-- Main Content -->
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Your Tasks</h1>

    <?php if($result->num_rows > 0): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
                <thead class="bg-blue-500 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left">ID</th>
                        <th class="py-3 px-4 text-left">Title</th>
                        <th class="py-3 px-4 text-left">Description</th>
                        <th class="py-3 px-4 text-left">Pickup</th>
                        <th class="py-3 px-4 text-left">Delivery</th>
                        <th class="py-3 px-4 text-left">Reward</th>
                        <th class="py-3 px-4 text-left">Status</th>
                        <th class="py-3 px-4 text-left">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($task = $result->fetch_assoc()): ?>
                        <tr class="border-b hover:bg-gray-100">
                            <td class="py-2 px-4"><?php echo $task['id']; ?></td>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($task['title']); ?></td>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($task['description']); ?></td>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($task['pickup']); ?></td>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($task['delivery']); ?></td>
                            <td class="py-2 px-4">Rs. <?php echo number_format($task['reward']); ?></td>
                            <td class="py-2 px-4 capitalize"><?php echo $task['status']; ?></td>
                            <td class="py-2 px-4"><?php echo $task['created_at']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-gray-700">You currently have no tasks assigned.</p>
    <?php endif; ?>
</div>

</body>
</html>