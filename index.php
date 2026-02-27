<?php
session_start();
require_once __DIR__ . "/config/database.php";


// Connect to database
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login form submission
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === '' || $password === '') {
        $login_error = 'Please enter both username and password.';
    } else {
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $dbUsername, $dbPassword, $role);
            $stmt->fetch();
            if (password_verify($password, $dbPassword)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $dbUsername;
                $_SESSION['role'] = $role;
                header("Location: index.php");
                exit();
            } else {
                $login_error = 'Invalid username or password.';
            }
        } else {
            $login_error = 'Invalid username or password.';
        }
        $stmt->close();
    }
}
?>
<!-- Include Header -->
<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>hireme.lk</title>
  <meta name="description" content="hireme.lk Delivery Service" />
  <meta name="author" content="hireme.lk" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=DM+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap">
  <style>
    body { font-family: 'DM Sans', sans-serif; }
    h1,h2,h3,h4,h5,h6 { font-family: 'Space Grotesk', sans-serif; }
  </style>
</head>
<body class="bg-gray-50 text-gray-900">

<!-- Navbar -->
<nav class="bg-white shadow-md">
  <div class="container mx-auto px-4 py-4 flex justify-between items-center">
    <div class="font-bold text-xl text-green-600">hireme.lk</div>
    <div class="space-x-4">
      <a href="post-task.php" class="text-green-600 hover:underline">Post Task</a>
      <a href="tasks.php" class="text-gray-700 hover:underline">Browse Tasks</a>
      <?php if(isset($_SESSION['username'])): ?>
        <span class="text-gray-800 font-semibold">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a href="logout.php" class="text-red-500 hover:underline">Logout</a>
      <?php else: ?>
        <button onclick="document.getElementById('loginModal').classList.remove('hidden')" class="text-gray-700 hover:underline">Login</button>
      <?php endif; ?>
    </div>
  </div>
</nav>

<!-- Login Modal -->
<div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
  <div class="bg-white p-6 rounded-lg w-96 relative">
    <button onclick="document.getElementById('loginModal').classList.add('hidden')" class="absolute top-2 right-2 text-gray-500">&times;</button>
    <h2 class="text-2xl font-bold mb-4 text-center">Login</h2>
    <?php if($login_error): ?>
      <div class="text-red-500 mb-2 text-center"><?php echo $login_error; ?></div>
    <?php endif; ?>
    <form method="POST" action="">
      <input type="text" name="username" placeholder="Username" class="w-full p-2 mb-3 border rounded" />
      <input type="password" name="password" placeholder="Password" class="w-full p-2 mb-3 border rounded" />
      <button type="submit" name="login" class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600">Login</button>
    </form>
  </div>
</div>

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center text-white">
  
  <!-- Background Image -->
  <div class="absolute inset-0">
    <img src="assets/hero-image.jpg" 
         alt="Delivery" 
         class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-black opacity-50"></div>
  </div>

  <!-- Content -->
  <div class="container mx-auto px-8 relative z-10">
    <h1 class="text-6xl md:text-7xl font-bold mb-6 leading-tight">
      Get anything picked up & delivered across <br>
      <span class="text-green-500">Sri Lanka</span>
    </h1>

    <p class="text-lg md:text-xl mb-8 max-w-xl">
      Post a task, set your budget, and let a trusted runner handle the rest. Documents, parcels, or anything in between.
    </p>

    <div class="space-x-4">
      <a href="post-task.php" class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600">
        Post a Task
      </a>
      <a href="tasks.php" class="px-6 py-3 border border-white rounded-lg hover:bg-white hover:text-gray-800">
        Browse Tasks
      </a>
    </div>
  </div>

</section>


<!-- How It Works -->
<section class="py-20 bg-gray-100">
  <div class="container mx-auto px-4 text-center mb-12">
    <h2 class="text-3xl md:text-4xl font-bold mb-3">How It Works</h2>
    <p class="text-gray-600 text-lg max-w-md mx-auto">Three simple steps to get your items delivered</p>
  </div>
  <div class="container mx-auto px-4 grid md:grid-cols-3 gap-8">
    <div class="bg-white p-6 rounded-xl shadow-md text-center">
      <div class="w-16 h-16 mx-auto mb-4 bg-green-100 flex items-center justify-center rounded-2xl">📦</div>
      <h3 class="font-bold text-xl mb-2">Post Your Task</h3>
      <p class="text-gray-600 text-sm">Describe the item you need picked up, the locations, and your budget.</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-md text-center">
      <div class="w-16 h-16 mx-auto mb-4 bg-green-100 flex items-center justify-center rounded-2xl">📍</div>
      <h3 class="font-bold text-xl mb-2">A Runner Accepts</h3>
      <p class="text-gray-600 text-sm">A verified task runner near the pickup location accepts your task.</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-md text-center">
      <div class="w-16 h-16 mx-auto mb-4 bg-green-100 flex items-center justify-center rounded-2xl">✅</div>
      <h3 class="font-bold text-xl mb-2">Get It Delivered</h3>
      <p class="text-gray-600 text-sm">Your item is picked up and delivered to you safely and on time.</p>
    </div>
  </div>
</section>

<!-- Features -->
<section class="py-20">
  <div class="container mx-auto px-4 text-center mb-12">
    <h2 class="text-3xl md:text-4xl font-bold mb-3">Why Choose hireme.lk?</h2>
    <p class="text-gray-600 text-lg max-w-md mx-auto">Built for Sri Lanka, trusted by the community</p>
  </div>
  <div class="container mx-auto px-4 grid md:grid-cols-3 gap-8 text-center">
    <div class="bg-white p-6 rounded-xl shadow-md">
      <div class="w-12 h-12 mx-auto mb-4 bg-green-100 flex items-center justify-center rounded-xl">🛡️</div>
      <h3 class="font-bold mb-2">Verified Runners</h3>
      <p class="text-gray-600 text-sm">All task runners go through phone verification for your safety.</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-md">
      <div class="w-12 h-12 mx-auto mb-4 bg-green-100 flex items-center justify-center rounded-xl">⭐</div>
      <h3 class="font-bold mb-2">Ratings & Reviews</h3>
      <p class="text-gray-600 text-sm">Transparent rating system so you always pick the best runner.</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-md">
      <div class="w-12 h-12 mx-auto mb-4 bg-green-100 flex items-center justify-center rounded-xl">🗺️</div>
      <h3 class="font-bold mb-2">Island-Wide Coverage</h3>
      <p class="text-gray-600 text-sm">From Colombo to Jaffna — get items delivered across Sri Lanka.</p>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="py-20 bg-green-500 text-white text-center">
  <div class="container mx-auto px-4">
    <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to get started?</h2>
    <p class="mb-8 text-lg">Join hundreds of Sri Lankans already using hireme.lk</p>
    <a href="post-task.php" class="px-6 py-3 bg-white text-green-500 rounded-lg mr-2 hover:bg-gray-100">Post Your First Task</a>
    <a href="tasks.php" class="px-6 py-3 border border-white rounded-lg hover:bg-white hover:text-green-500">Become a Runner</a>
  </div>
</section>


</body>
</html>
<!-- Include Footer -->
<?php include 'footer.php'; ?>
