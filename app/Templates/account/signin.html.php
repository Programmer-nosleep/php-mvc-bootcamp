<?php
use function App\site_local_url;
use function App\csrf_field;
use function App\escape;
?>

<section class="card stack auth">
  <div>
    <h1 class="h2">Welcome back</h1>
    <p class="muted">Masuk untuk melanjutkan.</p>
  </div>

  <form class="form" method="POST" action="<?= escape(site_local_url('/signin')) ?>">
    <?= csrf_field() ?>
    <div class="field">
      <label for="email">Email</label>
      <input id="email" name="email" type="email" autocomplete="email" required>
    </div>

    <div class="field">
      <label for="password">Password</label>
      <input id="password" name="password" type="password" autocomplete="current-password" required>
    </div>

    <div class="actions">
      <button class="btn primary" type="submit" name="signin_submit" value="1">Sign In</button>
      <a class="btn" href="<?= escape(site_local_url('/signup')) ?>">Create account</a>
    </div>
  </form>
</section>
