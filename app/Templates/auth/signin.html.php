<?php use function App\site_local_url; ?>

<div class="">
  <form method="POST" action="<?= site_local_url('/signin') ?>">
    <div class="">
      <label for="name">Name: </label>
      <input type="text" name="fullname" id="name" required>
    </div>
    <div class="">
      <label for="password">Password: </label>
      <input type="password" name="password" id="password" required>
    </div>

    <button type="submit" name="signin_submit" value="1">Sign In</button>
  </form>
</div>