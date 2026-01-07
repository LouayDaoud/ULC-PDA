<div class="page-header">
    <h1 class="page-title"><?= isset($maintenance) ? 'Modifier la maintenance' : 'Nouvelle maintenance' ?></h1>
    <a href="<?= rtrim(BASE_URL, '/') ?>/?page=maintenance" class="btn btn-link">← Retour</a>
</div>

<div class="card">
    <form method="POST" class="form">
        <?php if (!isset($maintenance)): ?>
        <div class="form-group">
            <label for="radio_id">Radio *</label>
            <select id="radio_id" name="radio_id" required>
                <option value="">Sélectionner une radio</option>
                <?php foreach ($radios as $radio): ?>
                <option value="<?= $radio['id'] ?>">
                    <?= htmlspecialchars($radio['code']) ?> 
                    (<?= ucfirst($radio['status']) ?>)
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php else: ?>
        <div class="info-box">
            <p><strong>Radio:</strong> <?= htmlspecialchars($maintenance['radio_code']) ?></p>
        </div>
        <?php endif; ?>

        <?php if (!isset($maintenance)): ?>
        <div class="form-group">
            <label for="reported_by">Signalé par *</label>
            <input type="text" id="reported_by" name="reported_by" required>
        </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="issue_type">Type de panne *</label>
            <input type="text" id="issue_type" name="issue_type" 
                   value="<?= htmlspecialchars($maintenance['issue_type'] ?? '') ?>" 
                   required placeholder="Ex: Batterie défectueuse, Antenne cassée...">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="3"><?= htmlspecialchars($maintenance['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="status">Statut *</label>
            <select id="status" name="status" required>
                <option value="en_attente" <?= ($maintenance['status'] ?? 'en_attente') === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                <option value="diagnostic" <?= ($maintenance['status'] ?? '') === 'diagnostic' ? 'selected' : '' ?>>En diagnostic</option>
                <option value="reparation" <?= ($maintenance['status'] ?? '') === 'reparation' ? 'selected' : '' ?>>En réparation</option>
                <option value="test" <?= ($maintenance['status'] ?? '') === 'test' ? 'selected' : '' ?>>Test</option>
                <option value="reparee" <?= ($maintenance['status'] ?? '') === 'reparee' ? 'selected' : '' ?>>Réparée</option>
                <option value="rebut" <?= ($maintenance['status'] ?? '') === 'rebut' ? 'selected' : '' ?>>Mise au rebut</option>
            </select>
        </div>

        <div class="form-group">
            <label for="comments">Commentaires</label>
            <textarea id="comments" name="comments" rows="3"><?= htmlspecialchars($maintenance['comments'] ?? '') ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-large">Enregistrer</button>
            <a href="<?= rtrim(BASE_URL, '/') ?>/?page=maintenance" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

