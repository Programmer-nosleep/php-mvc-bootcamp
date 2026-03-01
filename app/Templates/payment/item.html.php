<?php
use function App\escape;
use function App\site_local_url;
use function App\csrf_field;

$disabled = !empty($isFieldDisabled) && $isFieldDisabled === true ? 'disabled' : '';
?>

<section class="card stack">
  <div>
    <h1 class="h2">Item</h1>
    <p class="muted">Buat 1 item dukungan yang bisa dibagikan.</p>
  </div>

  <?php if ($disabled !== ''): ?>
    <div class="actions">
      <a class="btn primary" href="<?= escape(site_local_url('/payment')) ?>">Setup Payment</a>
    </div>
  <?php endif ?>

  <form class="form" method="POST" action="<?= escape(site_local_url('/item')) ?>">
    <?= csrf_field() ?>
    <div class="grid grid-2">
      <div class="field">
        <label for="id_name">ID Name</label>
        <input
          id="id_name"
          name="id_name"
          type="text"
          value="<?= escape((string)($idName ?? '')) ?>"
          placeholder="mis: my-latte"
          <?= $disabled ?>
          required
        >
        <p class="help">Dipakai untuk URL publik: `/p/{id-name}`. Hanya a-z 0-9 . - _</p>
      </div>

      <div class="field">
        <label for="item_name">Item name</label>
        <input
          id="item_name"
          name="item_name"
          type="text"
          value="<?= escape((string)($itemName ?? '')) ?>"
          placeholder="Buy me a latte"
          <?= $disabled ?>
          required
        >
      </div>
    </div>

    <div class="field">
      <label for="business_name">Business / Creator (optional)</label>
      <input
        id="business_name"
        name="business_name"
        type="text"
        value="<?= escape((string)($businessName ?? '')) ?>"
        placeholder="Nama brand kamu"
        <?= $disabled ?>
      >
    </div>

    <div class="field">
      <label for="summary">Summary</label>
      <textarea id="summary" name="summary" <?= $disabled ?> required><?= escape((string)($summary ?? '')) ?></textarea>
    </div>

    <div class="field">
      <label for="price">Price</label>
      <input
        id="price"
        name="price"
        type="number"
        inputmode="decimal"
        step="0.01"
        min="0"
        value="<?= escape((string)($price ?? '')) ?>"
        <?= $disabled ?>
      >
    </div>

    <div class="actions">
      <button class="btn primary" type="submit" name="item_submit" value="1" <?= $disabled ?>>Save</button>
      <a class="btn" href="<?= escape(site_local_url('/payment')) ?>">Back</a>
    </div>
  </form>
</section>

<?php if (!empty($shareItemUrl)): ?>
  <section class="card stack">
    <div>
      <h2 class="h3">Share link</h2>
      <p class="muted">Bagikan link ini ke siapa pun.</p>
    </div>

    <div class="copy-row">
      <input id="share_url" type="text" readonly value="<?= escape((string)$shareItemUrl) ?>">
      <button class="btn" type="button" data-copy="#share_url">Copy</button>
      <a class="btn primary" href="<?= escape((string)$shareItemUrl) ?>" target="_blank" rel="noopener">Open</a>
    </div>
  </section>
<?php endif ?>
