<?php
use function App\site_local_url;
?>

<section class="card stack">
  <h1 class="h2">Contact Us</h1>
  <p class="muted">Kirim pesan. Kami akan balas secepatnya.</p>

  <form class="form" method="POST" action="<?= site_local_url('/contact') ?>">
    <div class="grid grid-2">
      <div class="field">
        <label for="name">Name</label>
        <input id="name" name="name" type="text" autocomplete="name" required>
      </div>

      <div class="field">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" autocomplete="email" required>
      </div>
    </div>

    <div class="field">
      <label for="phone_number">Phone (optional)</label>
      <input id="phone_number" name="phone_number" type="tel" autocomplete="tel">
    </div>

    <div class="field">
      <label for="message">Message</label>
      <textarea id="message" name="message" required></textarea>
    </div>

    <div class="actions">
      <button class="btn primary" type="submit" name="contact_submit" value="1">Send Message</button>
    </div>
  </form>
</section>

