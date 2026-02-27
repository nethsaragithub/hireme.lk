<?php
session_start();
require_once "config/database.php";

$error = "";

if(isset($_POST['login'])) {
    $email_or_phone = trim($_POST['email_or_phone']);
    $password = $_POST['password'];

    if(empty($email_or_phone) || empty($password)) {
        $error = "Please enter email/phone and password.";
    } else {
        // Check user by email OR phone
        $stmt = $conn->prepare("SELECT id, email, phone, password, role, profile_image FROM users WHERE email = ? OR phone = ?");
        $stmt->bind_param("ss", $email_or_phone, $email_or_phone);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows === 1) {
            $stmt->bind_result($id, $email, $phone, $hashed_password, $role, $profile_image);
            $stmt->fetch();

            if(password_verify($password, $hashed_password)) {
                // Login successful
                $_SESSION['user_id'] = $id;
                $_SESSION['email'] = $email;
                $_SESSION['phone'] = $phone;
                $_SESSION['role'] = $role;
                $_SESSION['profile_image'] = $profile_image;

                // Redirect based on role
                if($role === 'admin') {
                    header("Location: admin-dashboard.php");
                    exit();
                } elseif($role === 'runner') {
                    header("Location: runner-dashboard.php");
                    exit();
                } else { // customer
                    header("Location: index.php");
                    exit();
                }
            } else {
                $error = "Incorrect password.";
            }

        } else {
            $error = "User not found.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - HireMe.lk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold text-center mb-6">Login</h2>

    <?php if($error): ?>
        <div class="bg-red-100 text-red-600 p-3 mb-4 rounded"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="email_or_phone" placeholder="Email or Phone"
            class="w-full p-3 mb-3 border rounded" required>

        <input type="password" name="password" placeholder="Password"
            class="w-full p-3 mb-3 border rounded" required>

        <button type="submit" name="login"
            class="w-full bg-green-500 text-white p-3 rounded hover:bg-green-600">
            Login
        </button>
    </form>

    <p class="text-center mt-4 text-sm">
        Don't have an account? 
        <a href="register.php" class="text-green-600 font-semibold">Register</a>
    </p>
</div>

</body>
</html>