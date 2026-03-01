<?php
use function App\escape;
?>

<section class="card stack">
  <h1 class="h2">About</h1>

  <p class="muted">
    <strong><?= escape((string)($siteName ?? '')) ?></strong> adalah aplikasi sederhana untuk menerima dukungan finansial dari audiens kamu.
    Kamu bisa menyiapkan pembayaran, membuat satu item dukungan, lalu membagikan link publik ke siapa pun.
  </p>

  <?php if (!empty($contactEmail)): ?>
    <p>
      Email: <a href="mailto:<?= escape((string)$contactEmail) ?>"><?= escape((string)$contactEmail) ?></a>
    </p>
  <?php endif ?>
</section>

