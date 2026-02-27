<?php
session_start();
require_once "config/database.php";

$error = "";
$success = "";

if(isset($_POST['register'])) {

    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validation
    if(empty($email) || empty($phone) || empty($password) || empty($role)) {
        $error = "All fields are required.";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {

        // Check if email or phone already exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = ? OR phone = ?");
        $check->bind_param("ss", $email, $phone);
        $check->execute();
        $check->store_result();

        if($check->num_rows > 0) {
            $error = "Email or phone already registered.";
        } else {

            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $profile_image = NULL;

            // PROFILE IMAGE UPLOAD
            if(!empty($_FILES['profile']['name'])) {
                $upload_dir = "uploads/profile/";

                if(!is_dir($upload_dir)){
                    mkdir($upload_dir, 0777, true);
                }

                $file_tmp = $_FILES['profile']['tmp_name'];
                $file_ext = strtolower(pathinfo($_FILES['profile']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg','jpeg','png','webp'];

                if(in_array($file_ext, $allowed)) {
                    $file_name = time() . "_" . uniqid() . "." . $file_ext;
                    move_uploaded_file($file_tmp, $upload_dir.$file_name);
                    $profile_image = $file_name;
                } else {
                    $error = "Invalid image type. Only JPG, PNG, WEBP allowed.";
                }
            }

            if(empty($error)) {
                $stmt = $conn->prepare("INSERT INTO users (email, phone, password, role, profile_image)
                                        VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $email, $phone, $hashed, $role, $profile_image);

                if($stmt->execute()) {
                    $success = "Registration successful! You can now login.";
                } else {
                    $error = "Something went wrong. Try again.";
                }
                $stmt->close();
            }
        }

        $check->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - HireMe.lk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">

    <h2 class="text-2xl font-bold text-center mb-6">Create Account</h2>

    <?php if($error): ?>
        <div class="bg-red-100 text-red-600 p-3 mb-4 rounded"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if($success): ?>
        <div class="bg-green-100 text-green-600 p-3 mb-4 rounded"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <input type="email" name="email" placeholder="Email"
            class="w-full p-3 mb-3 border rounded" required>

        <input type="text" name="phone" placeholder="Phone"
            class="w-full p-3 mb-3 border rounded" required>

        <input type="password" name="password" placeholder="Password"
            class="w-full p-3 mb-3 border rounded" required>

        <select name="role" class="w-full p-3 mb-3 border rounded" required>
            <option value="">Select Role</option>
            <option value="customer">Customer</option>
            <option value="runner">Runner</option>
            <option value="admin">Admin</option>
        </select>

        <label class="block mb-2 text-sm text-gray-600">Profile Image (Optional)</label>
        <input type="file" name="profile" class="w-full mb-4">

        <button type="submit" name="register"
            class="w-full bg-green-500 text-white p-3 rounded hover:bg-green-600">
            Register
        </button>
    </form>

    <p class="text-center mt-4 text-sm">
        Already have an account? 
        <a href="login.php" class="text-green-600 font-semibold">Login</a>
    </p>

</div>

</body>
</html>