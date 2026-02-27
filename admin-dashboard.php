<?php
session_start();
require_once "config/database.php";

// Check if user is admin
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Fetch all users
$result = $conn->query("SELECT id, email, phone, role, profile_image, created_at FROM users ORDER BY created_at DESC");
?>
<!-- Include Header -->
<?php include 'header.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - HireMe.lk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<!-- Navbar -->
<nav class="bg-green-500 text-white p-4 flex justify-between items-center">
    <div class="font-bold text-xl">HireMe.lk Admin Panel</div>
    <div>
        <span class="mr-4">Hello, Admin</span>
        <a href="logout.php" class="bg-white text-green-500 px-3 py-1 rounded hover:bg-gray-200">Logout</a>
    </div>
</nav>

<!-- Main Content -->
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">All Users</h1>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
            <thead class="bg-green-500 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">ID</th>
                    <th class="py-3 px-4 text-left">Email</th>
                    <th class="py-3 px-4 text-left">Phone</th>
                    <th class="py-3 px-4 text-left">Role</th>
                    <th class="py-3 px-4 text-left">Profile</th>
                    <th class="py-3 px-4 text-left">Registered At</th>
                </tr>
            </thead>
            <tbody>
                <?php while($user = $result->fetch_assoc()): ?>
                    <tr class="border-b hover:bg-gray-100">
                        <td class="py-2 px-4"><?php echo $user['id']; ?></td>
                        <td class="py-2 px-4"><?php echo htmlspecialchars($user['email']); ?></td>
                        <td class="py-2 px-4"><?php echo htmlspecialchars($user['phone']); ?></td>
                        <td class="py-2 px-4 capitalize"><?php echo $user['role']; ?></td>
                        <td class="py-2 px-4">
                            <?php if($user['profile_image']): ?>
                                <img src="../uploads/profile/<?php echo $user['profile_image']; ?>" class="w-10 h-10 rounded-full">
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td class="py-2 px-4"><?php echo $user['created_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>