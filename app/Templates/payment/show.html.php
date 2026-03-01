<?php
use function App\escape;
use function App\midtrans_client_key;
use function App\midtrans_is_enabled;
use function App\midtrans_snap_js_url;
use function App\site_name;

$idName = (string)($idName ?? '');
$itemName = (string)($itemName ?? '');
$businessName = (string)($businessName ?? '');
$summary = (string)($summary ?? '');
$currency = strtoupper((string)($currency ?? 'USD'));
$price = (float)($price ?? 0);
$creatorName = (string)($creatorName ?? '');

$canUseMidtrans = midtrans_is_enabled() && $currency === 'IDR' && $idName !== '';
?>

<section class="card stack">
  <p class="pill">Support</p>

  <div>
    <h1 class="h1"><?= escape($itemName) ?></h1>
    <?php if ($businessName !== ''): ?>
      <p class="muted"><?= escape($businessName) ?></p>
    <?php endif ?>
    <?php if ($creatorName !== ''): ?>
      <p class="muted">by <?= escape($creatorName) ?></p>
    <?php endif ?>
  </div>

  <?php if ($summary !== ''): ?>
    <div class="prose">
      <?= nl2br(escape($summary)) ?>
    </div>
  <?php endif ?>

  <div class="price-row">
    <div class="price">
      <span class="price-amount"><?= escape(number_format($price, 2)) ?></span>
      <span class="price-currency muted"><?= escape($currency) ?></span>
    </div>
  </div>

  <div class="actions">
    <a class="btn primary" href="<?= escape((string)($paymentLink ?? '')) ?>" target="_blank" rel="noopener">
      Pay with PayPal
    </a>

    <?php if ($canUseMidtrans): ?>
      <button class="btn" type="button" data-midtrans-pay data-id-name="<?= escape($idName) ?>">
        Pay with Midtrans
      </button>
    <?php endif ?>
  </div>

  <?php if (midtrans_is_enabled() && !$canUseMidtrans): ?>
    <p class="muted small">Midtrans di demo ini hanya aktif untuk mata uang IDR.</p>
  <?php endif ?>

  <p class="muted small">
    Powered by <?= escape(site_name()) ?>.
  </p>
</section>

<?php if ($canUseMidtrans): ?>
  <script src="<?= escape(midtrans_snap_js_url()) ?>" data-client-key="<?= escape(midtrans_client_key()) ?>"></script>
<?php endif ?>
