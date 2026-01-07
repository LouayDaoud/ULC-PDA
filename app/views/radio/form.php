<div class="page-header">
    <h1 class="page-title"><?= $radio ? 'Modifier la radio' : 'Ajouter une radio' ?></h1>
    <a href="<?= rtrim(BASE_URL, '/') ?>/?page=radio" class="btn btn-link">← Retour</a>
</div>

<div class="card">
    <form method="POST" class="form">
        <div class="form-group">
            <label for="code">Code radio *</label>
            <input type="text" id="code" name="code" value="<?= htmlspecialchars($radio['code'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="serial_number">Numéro de série</label>
            <input type="text" id="serial_number" name="serial_number" value="<?= htmlspecialchars($radio['serial_number'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="model">Modèle</label>
            <input type="text" id="model" name="model" value="<?= htmlspecialchars($radio['model'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="status">État *</label>
            <select id="status" name="status" required>
                <option value="disponible" <?= ($radio['status'] ?? 'disponible') === 'disponible' ? 'selected' : '' ?>>Disponible</option>
                <option value="empruntee" <?= ($radio['status'] ?? '') === 'empruntee' ? 'selected' : '' ?>>Empruntée</option>
                <option value="reparation" <?= ($radio['status'] ?? '') === 'reparation' ? 'selected' : '' ?>>En réparation</option>
                <option value="rebut" <?= ($radio['status'] ?? '') === 'rebut' ? 'selected' : '' ?>>Rebut</option>
            </select>
        </div>

        <div class="form-group">
            <label for="activity_id">Activité</label>
            <select id="activity_id" name="activity_id">
                <option value="">Aucune</option>
                <?php foreach ($activities as $activity): ?>
                <option value="<?= $activity['id'] ?>" <?= ($radio['activity_id'] ?? '') == $activity['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($activity['name']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="comments">Commentaires</label>
            <textarea id="comments" name="comments" rows="3"><?= htmlspecialchars($radio['comments'] ?? '') ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-large">Enregistrer</button>
            <a href="<?= rtrim(BASE_URL, '/') ?>/?page=radio" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

