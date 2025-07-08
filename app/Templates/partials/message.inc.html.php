<div class="center">
  <?php if (!empty($error_message)): ?>
    <span class="error">
      <?= $error_message ?>
    </span>
  <?php endif ?>

  <?php if (!empty($success_message)): ?>
    <span class="success">
      <?= $success_message ?>
    </span>
  <?php endif ?>

  <?php if (!empty($warn_message)): ?>
    <span class="warning">
      <?= $warn_message ?>
    </span>
  <?php endif ?>
</div>
