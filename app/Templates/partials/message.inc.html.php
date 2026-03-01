<?php use function App\escape; ?>

<?php if (!empty($error_message)): ?>
  <?php foreach ((array)$error_message as $message): ?>
    <div class="alert error"><?= escape((string)$message) ?></div>
  <?php endforeach ?>
<?php endif ?>

<?php if (!empty($success_message)): ?>
  <?php foreach ((array)$success_message as $message): ?>
    <div class="alert success"><?= escape((string)$message) ?></div>
  <?php endforeach ?>
<?php endif ?>

<?php if (!empty($warning_message)): ?>
  <?php foreach ((array)$warning_message as $message): ?>
    <div class="alert warning"><?= escape((string)$message) ?></div>
  <?php endforeach ?>
<?php endif ?>
