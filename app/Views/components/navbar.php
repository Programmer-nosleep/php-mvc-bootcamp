<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom" id="mainNavbar">
  <div class="container-fluid px-4">
    <span class="navbar-brand mb-0 h1"><?= $model['logo'] ?></span>

    <div class="d-flex align-items-center">
      <a href="/users/login" class="btn btn-outline-primary me-2">Login</a>
      <a href="/users/register" class="btn btn-primary me-3">Register</a>

      <!-- Switcher -->
      <button id="darkModeToggle" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-moon" id="themeIcon"></i>
      </button>
    </div>
  </div>
</nav>