<div class="login-container" style="background-image: url('<?= rtrim(BASE_URL, '/') ?>/assets/imp.jpeg'); background-size: cover; background-position: center center; background-repeat: no-repeat; background-attachment: fixed;">
    <div class="login-box">
        <h1 class="login-title"><?= APP_NAME ?></h1>
        <form method="POST" class="login-form">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-large">Se connecter</button>
        </form>
        <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
    </div>
</div>

