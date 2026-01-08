<div class="page-header">
    <h1 class="page-title">Importer des radios depuis Excel</h1>
    <a href="<?= rtrim(BASE_URL, '/') ?>/?page=radio" class="btn btn-link">← Retour</a>
</div>

<div class="card">
    <div class="info-box" style="margin-bottom: 1.5rem;">
        <h3 style="margin-bottom: 0.5rem;">Format du fichier Excel</h3>
        <p>Le fichier doit contenir les colonnes suivantes :</p>
        <ul style="margin-left: 1.5rem; margin-top: 0.5rem;">
            <li><strong>Modèle</strong> : Modèle de la radio</li>
            <li><strong>Nom</strong> : Code unique de la radio (obligatoire)</li>
            <li><strong>Statut</strong> : Actif, Empruntée, En réparation, Rebut (optionnel, défaut: Disponible)</li>
            <li><strong>SN</strong> : Numéro de série (optionnel)</li>
            <li><strong>Adresse MAC</strong> : Adresse MAC (optionnel)</li>
            <li><strong>Emplacement / Activité</strong> : Nom de l'activité (optionnel)</li>
        </ul>
        <p style="margin-top: 0.5rem;"><strong>Note :</strong> Les radios avec un code déjà existant seront mises à jour avec les nouvelles données.</p>
    </div>

    <?php if (isset($error) && $error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (isset($success) && $success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <strong>Erreurs rencontrées :</strong>
        <ul style="margin-top: 0.5rem; margin-left: 1.5rem;">
            <?php foreach ($errors as $err): ?>
            <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="form">
        <div class="form-group">
            <label for="excel_file">Fichier Excel (.xls, .xlsx, .csv) *</label>
            <input type="file" id="excel_file" name="excel_file" accept=".xls,.xlsx,.csv" required>
            <small style="color: #666;">Formats acceptés : Excel (.xls, .xlsx) ou CSV</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-large">📤 Importer</button>
            <a href="<?= rtrim(BASE_URL, '/') ?>/?page=radio" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

