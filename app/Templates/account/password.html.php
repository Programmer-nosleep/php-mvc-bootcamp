<?php
use function App\site_local_url;
use function App\csrf_field;
?>

<section class="card stack auth">
  <div>
    <h1 class="h2">Change Password</h1>
    <p class="muted">Gunakan password yang kuat dan unik.</p>
  </div>

  <form class="form" method="POST" action="<?= site_local_url('/account/password') ?>">
    <?= csrf_field() ?>
    <div class="field">
      <label for="current_password">Current password</label>
      <input id="current_password" name="current_password" type="password" autocomplete="current-password" required>
    </div>

    <div class="grid grid-2">
      <div class="field">
        <label for="new_password">New password</label>
        <input id="new_password" name="new_password" type="password" autocomplete="new-password" minlength="6" required>
      </div>

      <div class="field">
        <label for="confirm_password">Confirm new password</label>
        <input id="confirm_password" name="confirm_password" type="password" autocomplete="new-password" minlength="6" required>
      </div>
    </div>

    <div class="actions">
      <button class="btn primary" type="submit" name="password_submit" value="1">Update Password</button>
      <a class="btn" href="<?= site_local_url('/account/edit') ?>">Back</a>
    </div>
  </form>
</section>
