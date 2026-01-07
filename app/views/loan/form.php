<div class="page-header">
    <h1 class="page-title">Nouvel emprunt</h1>
    <a href="<?= rtrim(BASE_URL, '/') ?>/?page=loan" class="btn btn-link">← Retour</a>
</div>

<div class="card">
    <form method="POST" class="form">
        <div class="form-group">
            <label for="radio_id">Radio *</label>
            <select id="radio_id" name="radio_id" required>
                <option value="">Sélectionner une radio</option>
                <?php foreach ($availableRadios as $radio): ?>
                <option value="<?= $radio['id'] ?>">
                    <?= htmlspecialchars($radio['code']) ?> 
                    <?= $radio['serial_number'] ? ' (' . htmlspecialchars($radio['serial_number']) . ')' : '' ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="borrower_name">Nom de l'emprunteur *</label>
            <input type="text" id="borrower_name" name="borrower_name" required>
        </div>

        <div class="form-group">
            <label for="borrower_id">Matricule / ID</label>
            <input type="text" id="borrower_id" name="borrower_id">
        </div>

        <div class="form-group">
            <label for="activity_id">Activité *</label>
            <select id="activity_id" name="activity_id" required>
                <option value="">Sélectionner une activité</option>
                <?php foreach ($activities as $activity): ?>
                <option value="<?= $activity['id'] ?>"><?= htmlspecialchars($activity['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="due_at">Date de retour prévue</label>
            <input type="datetime-local" id="due_at" name="due_at">
        </div>

        <div class="form-group">
            <label for="state_out">État à la sortie</label>
            <input type="text" id="state_out" name="state_out" placeholder="Ex: Neuve, Marquée, Fissurée...">
        </div>

        <div class="form-group">
            <label for="comments">Commentaires</label>
            <textarea id="comments" name="comments" rows="3"></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-large">Enregistrer l'emprunt</button>
            <a href="<?= rtrim(BASE_URL, '/') ?>/?page=loan" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

