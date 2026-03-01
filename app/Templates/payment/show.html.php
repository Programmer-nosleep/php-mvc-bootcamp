<?php
use function App\escape;
use function App\site_name;

$itemName = (string)($itemName ?? '');
$businessName = (string)($businessName ?? '');
$summary = (string)($summary ?? '');
$currency = strtoupper((string)($currency ?? 'USD'));
$price = (float)($price ?? 0);
$creatorName = (string)($creatorName ?? '');
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
  </div>

  <p class="muted small">
    Powered by <?= escape(site_name()) ?>.
  </p>
</section>

