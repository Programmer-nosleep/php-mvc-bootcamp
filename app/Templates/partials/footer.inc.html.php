<?php
use function App\asset_url;
use function App\escape;
use function App\site_local_url;
use function App\site_name;
?>

  </main>

  <footer class="app-footer">
    <div class="container footer-inner">
      <div class="footer-brand">
        <strong><?= escape(site_name()) ?></strong>
        <span class="muted">Terima dukungan, sederhana.</span>
      </div>

      <div class="footer-links">
        <a href="<?= escape(site_local_url('/about')) ?>">About</a>
        <a href="<?= escape(site_local_url('/contact')) ?>">Contact</a>
        <a href="<?= escape(site_local_url('/')) ?>">Home</a>
      </div>

      <div class="footer-meta muted">
        &copy; <?= date('Y') ?> <a href="<?= escape(site_local_url('/')) ?>"><?= escape(site_name()) ?></a>
      </div>
    </div>
  </footer>
  <script src="<?= escape(asset_url('js/app.js')) ?>" defer></script>
</body>
</html>
