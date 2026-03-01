<?php
use App\Kernel\Http\Router;

use function App\asset_url;
use function App\csrf_token;
use function App\escape;
use function App\site_local_url;
use function App\site_name;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="color-scheme" content="light">
  <meta name="app-url" content="<?= escape(site_local_url('/')) ?>">
  <meta name="csrf-token" content="<?= escape(csrf_token()) ?>">
  <title><?= escape($title) ?> - <?= escape(site_name()) ?></title>
  <link rel="stylesheet" href="<?= escape(asset_url('css/app.css')) ?>">
</head>
<body>
  <header class="app-header">
    <div class="container nav">
      <a class="brand" href="<?= escape(site_local_url('/')) ?>"><?= escape(site_name()) ?></a>

      <nav class="nav-links" aria-label="Primary">
        <a class="<?= Router::doesContain('about') ? 'active' : '' ?>" href="<?= escape(site_local_url('/about')) ?>">About</a>
        <a class="<?= Router::doesContain('contact') ? 'active' : '' ?>" href="<?= escape(site_local_url('/contact')) ?>">Contact</a>

        <span class="nav-sep" aria-hidden="true"></span>

        <?php if (!empty($isLoggedIn) && $isLoggedIn === true): ?>
          <a class="<?= Router::doesContain('account/edit') ? 'active' : '' ?>" href="<?= escape(site_local_url('/account/edit')) ?>">Account</a>
          <a class="<?= Router::doesContain('account/password') ? 'active' : '' ?>" href="<?= escape(site_local_url('/account/password')) ?>">Password</a>
          <a class="<?= Router::doesContain('payment') ? 'active' : '' ?>" href="<?= escape(site_local_url('/payment')) ?>">Payment</a>
          <a class="<?= Router::doesContain('item') ? 'active' : '' ?>" href="<?= escape(site_local_url('/item')) ?>">Item</a>
          <a class="btn" href="<?= escape(site_local_url('/account/logout')) ?>">Logout</a>
        <?php else: ?>
          <a class="<?= Router::doesContain('signin') ? 'active' : '' ?>" href="<?= escape(site_local_url('/signin')) ?>">Sign In</a>
          <a class="btn primary <?= Router::doesContain('signup') ? 'active' : '' ?>" href="<?= escape(site_local_url('/signup')) ?>">Sign Up</a>
        <?php endif ?>
      </nav>
    </div>
  </header>

  <main class="container">
    <?php include __DIR__ . '/message.inc.html.php'; ?>
