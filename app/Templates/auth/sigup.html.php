<?php
use function App\Kernel\site_local_url;
?>

<div class="">
  <form method="POST" action="<?= site_local_url('/signup') ?>">
    <div class="">
      <label for="name">Name: </label>
      <input type="text" name="fullname" id="name" required>
    </div>
    <div class="">
      <label for="email">Email: </label>
      <input type="text" name="email" id="email" required>
    </div>
    <div class="">
      <label for="password">Password: </label>
      <input type="password" name="password" id="password" required>
    </div>
  </form>
</div>