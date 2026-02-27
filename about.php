<?php include "header.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>About Us - HireMe.lk</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        .about-hero {
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
            color: white;
            padding: 80px 0;
        }

        .about-card {
            border-radius: 15px;
            transition: 0.3s ease;
        }

        .about-card:hover {
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

        .stats-box {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
        }
    </style>
</head>

<body>

<!-- Hero Section -->
<section class="about-hero text-center">
    <div class="container">
        <h1 class="fw-bold">About HireMe.lk</h1>
        <p class="lead">Connecting people who need help with trusted local runners across Sri Lanka</p>
    </div>
</section>


<div class="container py-5">

    <!-- About Section -->
    <div class="row align-items-center mb-5">

        <!-- LEFT SIDE -->
        <div class="col-lg-6">

            <!-- Who We Are -->
            <div class="card border-0 shadow-sm mb-4 p-4 about-card">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box bg-primary text-white me-3">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h4 class="mb-0">Who We Are</h4>
                </div>

                <p class="text-muted">
                    HireMe.lk is a Sri Lankan task marketplace that connects people 
                    who need items picked up and delivered with reliable local runners. 
                    Whether it's documents, parcels, medicine, or electronics — 
                    we make delivery simple, fast, and affordable.
                </p>
            </div>

            <!-- Our Mission -->
            <div class="card border-0 shadow-sm p-4 about-card">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box bg-success text-white me-3">
                        <i class="bi bi-bullseye"></i>
                    </div>
                    <h4 class="mb-0">Our Mission</h4>
                </div>

                <p class="text-muted">
                    Our mission is to create earning opportunities for local communities 
                    while helping individuals and businesses move items quickly and safely.
                    We believe in trust, transparency, and empowering everyday people to earn.
                </p>
            </div>

        </div>

        <!-- RIGHT SIDE IMAGE -->
        <div class="col-lg-6 text-center">
            <img src="assets/delivery-image.jpg"
                 class="img-fluid rounded-4 shadow-lg"
                 alt="Delivery Service">
        </div>

    </div>


    <!-- Statistics Section -->
    <div class="stats-box text-center mb-5">
        <div class="row">
            <div class="col-md-4">
                <h2 class="fw-bold text-primary">1000+</h2>
                <p class="text-muted">Tasks Posted</p>
            </div>

            <div class="col-md-4">
                <h2 class="fw-bold text-success">500+</h2>
                <p class="text-muted">Active Runners</p>
            </div>

            <div class="col-md-4">
                <h2 class="fw-bold text-warning">98%</h2>
                <p class="text-muted">Successful Deliveries</p>
            </div>
        </div>
    </div>


    <!-- How It Works -->
    <div class="mb-5">
        <h3 class="mb-4 text-center fw-bold">How It Works</h3>

        <div class="row text-center">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 about-card">
                    <i class="bi bi-plus-circle text-primary fs-1 mb-3"></i>
                    <h5>Post a Task</h5>
                    <p class="text-muted">Describe what needs to be picked up and delivered.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 about-card">
                    <i class="bi bi-search text-success fs-1 mb-3"></i>
                    <h5>Get Matched</h5>
                    <p class="text-muted">Local runners browse and accept tasks near them.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 about-card">
                    <i class="bi bi-check-circle text-warning fs-1 mb-3"></i>
                    <h5>Get It Done</h5>
                    <p class="text-muted">Your item gets delivered safely and on time.</p>
                </div>
            </div>
        </div>
    </div>


    <!-- Why Choose Us -->
    <div class="bg-primary text-white p-5 rounded text-center">
        <h3 class="fw-bold">Why Choose HireMe.lk?</h3>
        <p class="mt-3">
            ✔ Fast & Reliable Delivery <br>
            ✔ Affordable Rewards <br>
            ✔ Local Community Support <br>
            ✔ Safe & Transparent System
        </p>
    </div>

</div>

<?php include "footer.php"; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>