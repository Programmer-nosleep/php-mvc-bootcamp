<?php use function App\site_local_url; ?>

<section class="card stack">
  <h1 class="h2">419 — Page Expired</h1>
  <p class="muted">
    Sesi kamu sudah kedaluwarsa atau token CSRF tidak valid. Silakan refresh halaman lalu coba lagi.
  </p>
  <div class="actions">
    <a class="btn primary" href="<?= site_local_url('/') ?>">Ke Beranda</a>
  </div>
</section>

