<?php include "header.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Safety Guidelines - HireMe.lk</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        .safety-hero {
            background: linear-gradient(135deg, #198754, #20c997);
            color: white;
            padding: 70px 0;
        }

        .safety-card {
            border-radius: 15px;
            transition: 0.3s ease;
        }

        .safety-card:hover {
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
<section class="safety-hero text-center">
    <div class="container">
        <h1 class="fw-bold">Safety First at HireMe.lk</h1>
        <p class="lead">Your security and trust are our top priorities</p>
    </div>
</section>

<div class="container py-5">

    <!-- For Task Posters -->
    <div class="mb-5">
        <h3 class="mb-4 fw-bold text-center">Safety for Task Posters</h3>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm p-4 safety-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-primary text-white me-3">
                            <i class="bi bi-person-check-fill"></i>
                        </div>
                        <h5 class="mb-0">Verify Runner Details</h5>
                    </div>
                    <p class="text-muted">
                        Always check the runner's profile, ratings, and reviews before assigning a task.
                    </p>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm p-4 safety-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-warning text-white me-3">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <h5 class="mb-0">Avoid Sharing Sensitive Info</h5>
                    </div>
                    <p class="text-muted">
                        Do not share personal financial information, passwords, or confidential data.
                    </p>
                </div>
            </div>
        </div>
    </div>


    <!-- For Runners -->
    <div class="mb-5">
        <h3 class="mb-4 fw-bold text-center">Safety for Runners</h3>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm p-4 safety-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-success text-white me-3">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <h5 class="mb-0">Meet in Safe Locations</h5>
                    </div>
                    <p class="text-muted">
                        Always pick up and deliver items in public or secure locations when possible.
                    </p>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm p-4 safety-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-danger text-white me-3">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <h5 class="mb-0">Report Suspicious Activity</h5>
                    </div>
                    <p class="text-muted">
                        If something feels unsafe or suspicious, report it immediately to HireMe.lk support.
                    </p>
                </div>
            </div>
        </div>
    </div>


    <!-- General Guidelines -->
    <div class="bg-light p-5 rounded shadow-sm text-center">
        <h3 class="fw-bold mb-3">General Safety Guidelines</h3>
        <p class="text-muted">
            ✔ Clearly describe tasks before accepting <br>
            ✔ Communicate only through official channels <br>
            ✔ Confirm item details before pickup <br>
            ✔ Keep records of transactions and communication
        </p>
    </div>

</div>

<?php include "footer.php"; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>