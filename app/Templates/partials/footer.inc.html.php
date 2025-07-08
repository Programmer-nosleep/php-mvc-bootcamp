<?php
use function App\Kernel\site_local_url;
?>
<body> 
  <hr>
  <footer>
      &copy; <?= date('Y') ?> <?= site_local_url() ?> <?= site_local_url() ?>
  </footer>
</body>