<?php
use function App\escape;
use function App\site_local_url;

$paypalEmail = (string)($paypalEmail ?? '');
$currency = strtoupper((string)($currency ?? 'USD'));

$currencies = ['USD', 'IDR', 'EUR', 'GBP', 'SGD', 'AUD', 'JPY'];
?>

<section class="card stack">
  <div>
    <h1 class="h2">Payment Gateway</h1>
    <p class="muted">Simpan detail pembayaran untuk menerima dukungan.</p>
  </div>

  <form class="form" method="POST" action="<?= site_local_url('/payment') ?>">
    <div class="grid grid-2">
      <div class="field">
        <label for="paypal_email">PayPal email</label>
        <input
          id="paypal_email"
          name="paypal_email"
          type="email"
          autocomplete="email"
          value="<?= escape($paypalEmail) ?>"
          required
        >
      </div>

      <div class="field">
        <label for="currency">Currency</label>
        <select id="currency" name="currency" required>
          <?php foreach ($currencies as $code): ?>
            <option value="<?= escape($code) ?>" <?= $currency === $code ? 'selected' : '' ?>>
              <?= escape($code) ?>
            </option>
          <?php endforeach ?>
        </select>
        <p class="help">Gunakan kode 3 huruf.</p>
      </div>
    </div>

    <div class="actions">
      <button class="btn primary" type="submit" name="payment_submit" value="1">Save</button>
      <a class="btn" href="<?= site_local_url('/item') ?>">Next: Item</a>
    </div>
  </form>
</section>

