<div class="page-header">
    <h1 class="page-title"><?= $activity ? 'Modifier l\'activité' : 'Ajouter une activité' ?></h1>
    <a href="<?= rtrim(BASE_URL, '/') ?>/?page=activity" class="btn btn-link">← Retour</a>
</div>

<div class="card">
    <form method="POST" class="form">
        <div class="form-group">
            <label for="name">Nom *</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($activity['name'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="3"><?= htmlspecialchars($activity['description'] ?? '') ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-large">Enregistrer</button>
            <a href="<?= rtrim(BASE_URL, '/') ?>/?page=activity" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

