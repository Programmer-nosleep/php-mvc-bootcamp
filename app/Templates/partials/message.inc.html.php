<?php if (!empty($error_message)): ?>
  <?php foreach ((array)$error_message as $message): ?>
    <div class="alert error"><?= $message ?></div>
  <?php endforeach ?>
<?php endif ?>

<?php if (!empty($success_message)): ?>
  <?php foreach ((array)$success_message as $message): ?>
    <div class="alert success"><?= $message ?></div>
  <?php endforeach ?>
<?php endif ?>

<?php if (!empty($warning_message)): ?>
  <?php foreach ((array)$warning_message as $message): ?>
    <div class="alert warning"><?= $message ?></div>
  <?php endforeach ?>
<?php endif ?>

