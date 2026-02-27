<?php
require_once "config/database.php";
require_once "includes/auth.php";
requireLogin();

$id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<h2>My Profile</h2>

<?php if($user['profile_image']): ?>
    <img src="uploads/profile/<?php echo $user['profile_image']; ?>" width="150">
<?php else: ?>
    <p>No Profile Image</p>
<?php endif; ?>

<p>Email: <?php echo $user['email']; ?></p>
<p>Phone: <?php echo $user['phone']; ?></p>
<p>Role: <?php echo $user['role']; ?></p>

<a href="dashboard.php">Back</a>
