<?php
use function App\escape;
use function App\site_local_url;
?>

<section class="hero card">
  <div class="stack">
    <p class="pill">Flat • Simple • Fast</p>
    <h1 class="h1">Get your support page ready in minutes</h1>
    <p class="muted">
      Buat halaman dukungan untuk karya kamu. Atur metode pembayaran, buat item, lalu bagikan link-nya.
    </p>

    <div class="actions">
      <?php if (!empty($isLoggedIn) && $isLoggedIn === true): ?>
        <a class="btn primary" href="<?= escape(site_local_url('/payment')) ?>">Setup Payment</a>
        <a class="btn" href="<?= escape(site_local_url('/item')) ?>">Buat Item</a>
      <?php else: ?>
        <a class="btn primary" href="<?= escape(site_local_url('/signup')) ?>">Mulai Gratis</a>
        <a class="btn" href="<?= escape(site_local_url('/signin')) ?>">Sign In</a>
      <?php endif ?>
    </div>
  </div>
</section>

<?php if (!empty($isLoggedIn) && $isLoggedIn === true): ?>
  <section class="card">
    <h2 class="h3">Halo, <?= escape((string)($name ?? '')) ?></h2>
    <p class="muted">
      Lanjutkan setup akun kamu supaya halaman dukungan bisa langsung dipakai.
    </p>
  </section>
<?php endif ?>

<section class="grid grid-3">
  <div class="card">
    <h3 class="h4">1) Payment</h3>
    <p class="muted">Simpan email PayPal dan pilih mata uang.</p>
  </div>
  <div class="card">
    <h3 class="h4">2) Item</h3>
    <p class="muted">Buat item dukungan dan harga yang kamu inginkan.</p>
  </div>
  <div class="card">
    <h3 class="h4">3) Share</h3>
    <p class="muted">Bagikan URL publik dan terima dukungan dari siapa pun.</p>
  </div>
</section>
