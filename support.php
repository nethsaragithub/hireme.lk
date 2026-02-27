<?php
session_start();
require_once __DIR__ . "/config/database.php";

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name    = mysqli_real_escape_string($conn, trim($_POST["name"]));
    $email   = mysqli_real_escape_string($conn, trim($_POST["email"]));
    $message = mysqli_real_escape_string($conn, trim($_POST["message"]));

    if (empty($name) || empty($email) || empty($message)) {
        $error = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address!";
    } else {

        $sql = "INSERT INTO support_messages (name, email, message) 
                VALUES ('$name', '$email', '$message')";

        if (mysqli_query($conn, $sql)) {
            $success = "✅ Your message has been submitted successfully!";
        } else {
            $error = "❌ Error saving message: " . mysqli_error($conn);
        }
    }
}
?>

<?php include "header.php"; ?>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.support-hero {
    background: linear-gradient(135deg, #0d6efd, #6610f2);
    color: white;
    padding: 70px 0;
}

.support-card {
    border-radius: 15px;
}

</style>

<!-- Hero Section -->
<section class="support-hero text-center">
    <div class="container">
        <h1 class="fw-bold">Contact HireMe.lk Support</h1>
        <p class="lead">We’re here to help you anytime</p>
    </div>
</section>

<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow-sm border-0 p-4 support-card">

                <?php if ($success): ?>
                    <div class="alert alert-success text-center">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger text-center">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Your Message</label>
                        <textarea name="message" rows="5" class="form-control" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Submit Message
                    </button>

                </form>

            </div>

        </div>
    </div>

</div>

<?php include "footer.php"; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>