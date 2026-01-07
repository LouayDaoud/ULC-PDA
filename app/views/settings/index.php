<div class="page-header">
    <h1 class="page-title">Paramètres</h1>
</div>

<div class="card">
    <h2 class="section-title">Changer le mot de passe</h2>
    <form method="POST" class="form">
        <input type="hidden" name="change_password" value="1">
        
        <div class="form-group">
            <label for="old_password">Ancien mot de passe *</label>
            <input type="password" id="old_password" name="old_password" required>
        </div>

        <div class="form-group">
            <label for="new_password">Nouveau mot de passe *</label>
            <input type="password" id="new_password" name="new_password" required minlength="6">
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirmer le mot de passe *</label>
            <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-large">Changer le mot de passe</button>
        </div>
    </form>
</div>

<div class="card">
    <h2 class="section-title">Navigation</h2>
    <div class="nav-links">
        <a href="<?= rtrim(BASE_URL, '/') ?>/?page=settings&action=audit" class="btn btn-secondary">📋 Journal d'audit</a>
        <a href="<?= rtrim(BASE_URL, '/') ?>/?page=settings&action=loginHistory" class="btn btn-secondary">🔐 Historique des connexions</a>
    </div>
</div>

