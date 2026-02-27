<?php include "header.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Terms & Conditions - HireMe.lk</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        .terms-hero {
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            color: white;
            padding: 70px 0;
        }

        .terms-card {
            border-radius: 15px;
            transition: 0.3s ease;
        }

        .terms-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .icon-box {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 22px;
        }
    </style>
</head>

<body>

<!-- Hero Section -->
<section class="terms-hero text-center">
    <div class="container">
        <h1 class="fw-bold">Terms & Conditions</h1>
        <p class="lead">Please read these terms carefully before using HireMe.lk</p>
    </div>
</section>

<div class="container py-5">

    <!-- Introduction -->
    <div class="mb-5">
        <h3 class="fw-bold mb-3">1. Introduction</h3>
        <p class="text-muted">
            Welcome to <strong>hireme.lk</strong>. By accessing or using our platform, you agree to comply 
            with these Terms and Conditions. If you do not agree, please do not use our services.
        </p>
    </div>

    <!-- User Responsibilities -->
    <div class="mb-5">
        <h3 class="fw-bold mb-4 text-center">2. User Responsibilities</h3>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm p-4 terms-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-primary text-white me-3">
                            <i class="bi bi-person-fill-check"></i>
                        </div>
                        <h5 class="mb-0">Accurate Information</h5>
                    </div>
                    <p class="text-muted">
                        Users must provide accurate and truthful information when creating accounts 
                        and posting tasks.
                    </p>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm p-4 terms-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-danger text-white me-3">
                            <i class="bi bi-x-circle-fill"></i>
                        </div>
                        <h5 class="mb-0">Prohibited Activities</h5>
                    </div>
                    <p class="text-muted">
                        Users must not engage in fraudulent, illegal, or harmful activities 
                        through the platform.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments -->
    <div class="mb-5">
        <h3 class="fw-bold mb-3">3. Payments & Fees</h3>
        <p class="text-muted">
            hireme.lk may charge service fees for completed tasks. 
            All payments must be processed through the official platform.
        </p>
    </div>

    <!-- Liability -->
    <div class="mb-5">
        <h3 class="fw-bold mb-3">4. Limitation of Liability</h3>
        <p class="text-muted">
            hireme.lk acts as a marketplace connecting task posters and runners. 
            We are not responsible for disputes, damages, or losses arising from user interactions.
        </p>
    </div>

    <!-- Account Termination -->
    <div class="mb-5">
        <h3 class="fw-bold mb-3">5. Account Termination</h3>
        <p class="text-muted">
            We reserve the right to suspend or terminate accounts that violate 
            our terms without prior notice.
        </p>
    </div>

    <!-- Changes -->
    <div class="bg-light p-5 rounded shadow-sm text-center">
        <h3 class="fw-bold mb-3">6. Changes to Terms</h3>
        <p class="text-muted">
            hireme.lk reserves the right to update these Terms & Conditions at any time. 
            Continued use of the platform constitutes acceptance of the revised terms.
        </p>
    </div>

</div>

<?php include "footer.php"; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>