<?php
use function App\escape;
use function App\site_local_url;
use function App\csrf_field;

$user = is_array($user ?? null) ? $user : [];
?>

<section class="card stack">
  <div>
    <h1 class="h2">Edit Account</h1>
    <p class="muted">Perbarui nama atau email kamu.</p>
  </div>

  <form class="form" method="POST" action="<?= site_local_url('/account/edit') ?>">
    <?= csrf_field() ?>
    <div class="grid grid-2">
      <div class="field">
        <label for="fullname">Full name</label>
        <input
          id="fullname"
          name="fullname"
          type="text"
          autocomplete="name"
          value="<?= escape((string)($user['fullname'] ?? '')) ?>"
          required
        >
      </div>

      <div class="field">
        <label for="email">Email</label>
        <input
          id="email"
          name="email"
          type="email"
          autocomplete="email"
          value="<?= escape((string)($user['email'] ?? '')) ?>"
          required
        >
      </div>
    </div>

    <div class="actions">
      <button class="btn primary" type="submit" name="edit_submit" value="1">Save Changes</button>
      <a class="btn" href="<?= site_local_url('/account/password') ?>">Change Password</a>
    </div>
  </form>
</section>
