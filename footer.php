<footer class="bg-gray-900 text-gray-200 py-12">
  <div class="container mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

      <!-- Logo and Description -->
      <div class="space-y-3">
        <a href="index.php" class="flex items-center gap-2">
          <div class="w-8 h-8 rounded-lg bg-green-500 flex items-center justify-center">
            <!-- You can replace with an actual SVG logo or img -->
            <span class="text-white font-bold">H</span>
          </div>
          <span class="font-heading font-bold text-lg text-white">
            hireme<span class="text-green-500">.lk</span>
          </span>
        </a>
        <p class="text-sm text-gray-400 max-w-xs">
          Sri Lanka's trusted task-based item retrieval and delivery platform.
        </p>
      </div>

      <!-- Platform Links -->
      <div>
        <h4 class="font-heading font-semibold text-white mb-3">Platform</h4>
        <ul class="space-y-2 text-sm">
          <li><a href="tasks.php" class="hover:text-green-500 transition-colors">Browse Tasks</a></li>
          <li><a href="post-task.php" class="hover:text-green-500 transition-colors">Post a Task</a></li>
          <li><a href="index.php#how-it-works" class="hover:text-green-500 transition-colors">How It Works</a></li>
        </ul>
      </div>

      <!-- Company Links -->
      <div>
        <h4 class="font-heading font-semibold text-white mb-3">Company</h4>
        <ul class="space-y-2 text-sm">
          <li><a href="about.php" class="hover:text-green-500 transition-colors">About Us</a></li>
          <li><a href="safety.php" class="hover:text-green-500 transition-colors">Safety</a></li>
          <li><a href="support.php" class="hover:text-green-500 transition-colors">Support</a></li>
        </ul>
      </div>

      <!-- Legal Links -->
      <div>
        <h4 class="font-heading font-semibold text-white mb-3">Legal</h4>
        <ul class="space-y-2 text-sm">
          <li><a href="terms.php" class="hover:text-green-500 transition-colors">Terms of Service</a></li>
          <li><a href="privacy.php" class="hover:text-green-500 transition-colors">Privacy Policy</a></li>
        </ul>
      </div>

    </div>

    <!-- Footer Bottom -->
    <div class="border-t border-gray-700 mt-10 pt-6 text-center text-sm text-gray-400">
      &copy; <?php echo date('Y'); ?> hireme.lk – All rights reserved.
    </div>
  </div>
</footer>
