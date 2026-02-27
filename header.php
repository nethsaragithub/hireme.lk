<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HireMe.lk</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=DM+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap">
  <style>
    body { font-family: 'DM Sans', sans-serif; }
    h1,h2,h3,h4,h5,h6 { font-family: 'Space Grotesk', sans-serif; }
  </style>
</head>
<body class="bg-gray-50 text-gray-900">

<header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-200">
  <div class="container mx-auto flex items-center justify-between h-16 px-4">
    <a href="index.php" class="flex items-center gap-2">
      <div class="w-9 h-9 rounded-lg bg-green-500 flex items-center justify-center">
        <span class="text-white font-bold">H</span>
      </div>
      <span class="font-heading font-bold text-xl text-gray-900">hireme<span class="text-green-500">.lk</span></span>
    </a>

    <!-- Desktop Links -->
    <nav class="hidden md:flex items-center gap-1">
      <?php
      $links = [
        ['href'=>'index.php','label'=>'Home'],
        ['href'=>'tasks.php','label'=>'Browse Tasks'],
        ['href'=>'post-task.php','label'=>'Post a Task'],
        
      ];
      $current = basename($_SERVER['PHP_SELF']);
      foreach($links as $link):
        $active = ($current == basename($link['href'])) ? "bg-green-100 text-green-600" : "text-gray-600 hover:text-gray-900 hover:bg-gray-100";
      ?>
        <a href="<?php echo $link['href']; ?>" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo $active; ?>">
          <?php echo $link['label']; ?>
        </a>
      <?php endforeach; ?>
    </nav>

    <!-- Desktop Buttons -->
    <div class="hidden md:flex items-center gap-2">
      <?php if(isset($_SESSION['username']) && isset($_SESSION['role'])): ?>
          <span class="text-gray-800 font-semibold">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></span>

          <?php if($_SESSION['role'] === 'admin'): ?>
            <a href="admin-dashboard.php" class="px-3 py-2 bg-green-500 text-white rounded hover:bg-green-600">Admin Dashboard</a>
          <?php elseif($_SESSION['role'] === 'runner'): ?>
            <a href="runner-dashboard.php" class="px-3 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Runner Dashboard</a>
          <?php endif; ?>

         <a href="logout.php">Logout</a>
      <?php else: ?>
          <button onclick="document.getElementById('loginModal').classList.remove('hidden')" class="px-3 py-2 bg-gray-100 rounded hover:bg-gray-200">Log In</button>
          <a href="register.php" class="px-3 py-2 bg-green-500 text-white rounded hover:bg-green-600">Sign Up</a>
      <?php endif; ?>
    </div>

    <!-- Mobile Menu Button -->
    <button class="md:hidden text-gray-900" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>
  </div>

  <!-- Mobile Menu -->
  <div id="mobileMenu" class="md:hidden hidden border-t border-gray-200 bg-white px-4 pb-4">
    <nav class="flex flex-col gap-1 pt-2">
      <?php foreach($links as $link):
        $active = ($current == basename($link['href'])) ? "bg-green-100 text-green-600" : "text-gray-600 hover:text-gray-900 hover:bg-gray-100";
      ?>
        <a href="<?php echo $link['href']; ?>" class="px-4 py-2 rounded-lg text-sm font-medium <?php echo $active; ?>">
          <?php echo $link['label']; ?>
        </a>
      <?php endforeach; ?>
    </nav>

    <div class="flex flex-col mt-3 gap-2">
      <?php if(isset($_SESSION['username']) && isset($_SESSION['role'])): ?>
          <span class="text-gray-800 font-semibold px-4">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></span>

          <?php if($_SESSION['role'] === 'admin'): ?>
            <a href="admin-dashboard.php" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Admin Dashboard</a>
          <?php elseif($_SESSION['role'] === 'runner'): ?>
            <a href="runner-dashboard.php" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Runner Dashboard</a>
          <?php endif; ?>

          <a href="logout.php" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Logout</a>

      <?php else: ?>
          <button onclick="document.getElementById('loginModal').classList.remove('hidden')" class="px-4 py-2 bg-gray-100 rounded hover:bg-gray-200">Log In</button>
          <a href="register.php" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Sign Up</a>
      <?php endif; ?>
    </div>
  </div>
</header>