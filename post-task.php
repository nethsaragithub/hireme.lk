<?php
include "config/database.php";


$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $pickup = trim($_POST['pickup']);
    $delivery = trim($_POST['delivery']);
    $reward = trim($_POST['reward']);

    if (empty($title) || empty($description) || empty($pickup) || empty($delivery) || empty($reward)) {
        $error = "Please fill all required fields.";
    } else {

        $stmt = $conn->prepare("INSERT INTO tasks (title, description, category, pickup, delivery, reward, status) VALUES (?, ?, ?, ?, ?, ?, 'open')");
        $stmt->bind_param("ssssss", $title, $description, $category, $pickup, $delivery, $reward);

        if ($stmt->execute()) {
            header("Location: tasks.php?success=1");
            exit();
        } else {
            $error = "Something went wrong. Try again.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Post a Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'header.php'; ?>
<div class="container py-5" style="max-width:700px;">

    <div class="card shadow">
        <div class="card-body">

            <h3 class="mb-3">Post a New Task</h3>
            <p class="text-muted">
                Describe what you need picked up and delivered. Be specific so runners know exactly what to expect.
            </p>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Task Title</label>
                    <input type="text" name="title" class="form-control"
                        placeholder="e.g., Pick up documents from Kandy" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4" class="form-control"
                        placeholder="Describe the item, instructions, reference numbers..." required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select" required>
                        <option value="">Select category</option>
                        <option value="documents">Documents</option>
                        <option value="parcels">Parcels</option>
                        <option value="medicine">Medicine</option>
                        <option value="electronics">Electronics</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pickup Location</label>
                        <input type="text" name="pickup" class="form-control"
                            placeholder="e.g., Kandy DS Office" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Delivery Location</label>
                        <input type="text" name="delivery" class="form-control"
                            placeholder="e.g., Colombo 07" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Reward (Rs.)</label>
                    <input type="number" name="reward" class="form-control"
                        placeholder="1500" min="100" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Post Task
                </button>

            </form>
        </div>
    </div>

</div>

<?php include "footer.php"; ?>

</body>
</html>