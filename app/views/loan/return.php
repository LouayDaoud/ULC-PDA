<div class="page-header">
    <h1 class="page-title">Retour d'emprunt</h1>
    <a href="<?= rtrim(BASE_URL, '/') ?>/?page=loan" class="btn btn-link">← Retour</a>
</div>

<div class="card">
    <div class="info-box">
        <p><strong>Radio:</strong> <?= htmlspecialchars($loan['radio_code']) ?></p>
        <p><strong>Emprunteur:</strong> <?= htmlspecialchars($loan['borrower_name']) ?></p>
        <p><strong>Emprunté le:</strong> <?= date('d/m/Y H:i', strtotime($loan['borrowed_at'])) ?></p>
        <?php if ($loan['due_at']): ?>
        <p><strong>Retour prévu:</strong> <?= date('d/m/Y', strtotime($loan['due_at'])) ?></p>
        <?php endif; ?>
        <p><strong>État à la sortie:</strong> <?= htmlspecialchars($loan['state_out'] ?? '-') ?></p>
    </div>

    <form method="POST" class="form">
        <div class="form-group">
            <label for="state_in">État au retour *</label>
            <input type="text" id="state_in" name="state_in" required placeholder="Ex: Bon état, Endommagée, Accessoires manquants...">
        </div>

        <div class="form-group">
            <label for="comments">Remarques</label>
            <textarea id="comments" name="comments" rows="3" placeholder="Casse, perte d'accessoires, etc."></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success btn-large">Enregistrer le retour</button>
            <a href="<?= rtrim(BASE_URL, '/') ?>/?page=loan" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

