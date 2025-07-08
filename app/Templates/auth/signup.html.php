<body>
    <?php use function App\Kernel\site_local_url; ?>
    <?php if (!empty($error_message)): ?>
        <?= $error_message ?>
    <?php endif; ?>

    <div class="">
        <form method="POST" action="<?= site_local_url('/?uri=signup') ?>">
            <div class="">
                <label for="name">Name: </label>
                <input type="text" name="fullname" id="name" required>
            </div>
            <div class="">
                <label for="email">Email: </label>
                <input type="text" name="email" id="email" required>
            </div>
            <div class="">
                <label for="password">Password: </label>
                <input type="password" name="password" id="password" required>
            </div>

            <button type="submit" name="signup_submit" value="1">Sign UP</button>
        </form>
    </div>
</body>
</html>