<?php
use function App\site_local_url;
use function App\csrf_field;
?>

<section class="card stack auth">
  <div>
    <h1 class="h2">Create account</h1>
    <p class="muted">Buat akun untuk mulai menerima dukungan.</p>
  </div>

  <form class="form" method="POST" action="<?= site_local_url('/signup') ?>">
    <?= csrf_field() ?>
    <div class="field">
      <label for="fullname">Full name</label>
      <input id="fullname" name="fullname" type="text" autocomplete="name" required>
    </div>

    <div class="field">
      <label for="email">Email</label>
      <input id="email" name="email" type="email" autocomplete="email" required>
    </div>

    <div class="field">
      <label for="password">Password</label>
      <input id="password" name="password" type="password" autocomplete="new-password" minlength="6" required>
      <p class="help">Minimal 6 karakter.</p>
    </div>

    <div class="actions">
      <button class="btn primary" type="submit" name="signup_submit" value="1">Sign Up</button>
      <a class="btn" href="<?= site_local_url('/signin') ?>">I already have an account</a>
    </div>
  </form>
</section>
