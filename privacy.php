<?php include "header.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - HireMe.lk</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: url('assets/privacy-image.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            position: relative;
        }

        .overlay {
            background: rgba(0, 0, 0, 0.65);
            min-height: 100vh;
            padding: 80px 20px;
        }

        .privacy-hero {
            text-align: center;
            color: #fff;
            margin-bottom: 60px;
        }

        .privacy-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }

        .privacy-section h3 {
            font-weight: 700;
            margin-bottom: 15px;
            color: #0d6efd;
        }

        .privacy-section p {
            color: #333;
            line-height: 1.6;
        }
    </style>
</head>
<body>

<div class="overlay">
    <!-- Hero Section -->
    <section class="privacy-hero">
        <div class="container">
            <h1 class="fw-bold">Privacy Policy</h1>
            <p class="lead">Your privacy and security are important to us at HireMe.lk</p>
        </div>
    </section>

    <div class="container">

        <!-- Introduction -->
        <div class="privacy-section">
            <h3>1. Introduction</h3>
            <p>
                Welcome to <strong>HireMe.lk</strong>. We are committed to protecting your personal information 
                and your right to privacy. This Privacy Policy explains how we collect, use, and protect your data.
            </p>
        </div>

        <!-- Information Collection -->
        <div class="privacy-section">
            <h3>2. Information We Collect</h3>
            <p>
                We may collect personal information such as your name, email, phone number, and task-related details 
                when you use our platform.
            </p>
        </div>

        <!-- How We Use Information -->
        <div class="privacy-section">
            <h3>3. How We Use Your Information</h3>
            <p>
                Your information is used to provide and improve our services, process payments, communicate with users, 
                and ensure a safe environment for all users.
            </p>
        </div>

        <!-- Sharing Information -->
        <div class="privacy-section">
            <h3>4. Sharing Your Information</h3>
            <p>
                We do not sell your personal information. Information may be shared with trusted third-party service 
                providers to deliver our services or when required by law.
            </p>
        </div>

        <!-- Security Measures -->
        <div class="privacy-section">
            <h3>5. Security Measures</h3>
            <p>
                We use appropriate technical and organizational measures to protect your data against unauthorized access, 
                loss, or misuse.
            </p>
        </div>

        <!-- Changes to Privacy Policy -->
        <div class="privacy-section text-center">
            <h3>6. Changes to Privacy Policy</h3>
            <p>
                HireMe.lk may update this Privacy Policy from time to time. Continued use of the platform 
                constitutes acceptance of the updated policy.
            </p>
        </div>

    </div>
</div>

<?php include "footer.php"; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>