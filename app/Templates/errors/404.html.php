<?php use function App\site_local_url; ?>

<section class="card stack">
  <h1 class="h2">404 — Page Not Found</h1>
  <p class="muted">Halaman yang kamu cari tidak ditemukan.</p>
  <div class="actions">
    <a class="btn" href="<?= site_local_url('/') ?>">Kembali ke Beranda</a>
  </div>
</section>
