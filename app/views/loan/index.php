<div class="page-header">
    <h1 class="page-title">Emprunts</h1>
    <a href="<?= rtrim(BASE_URL, '/') ?>/?page=loan&action=create" class="btn btn-primary">➕ Nouvel emprunt</a>
</div>

<?php if (!empty($overdue)): ?>
<div class="alert alert-warning">
    <strong>⚠️ <?= count($overdue) ?> emprunt(s) en retard</strong>
</div>
<?php endif; ?>

<div class="filters">
    <form method="GET" class="filter-form">
        <input type="hidden" name="page" value="loan">
        <input type="text" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>" class="input">
        <select name="status" class="select">
            <option value="">Tous les statuts</option>
            <option value="en_cours" <?= ($filters['status'] ?? '') === 'en_cours' ? 'selected' : '' ?>>En cours</option>
            <option value="retourne" <?= ($filters['status'] ?? '') === 'retourne' ? 'selected' : '' ?>>Retourné</option>
            <option value="en_retard" <?= ($filters['status'] ?? '') === 'en_retard' ? 'selected' : '' ?>>En retard</option>
            <option value="perdu" <?= ($filters['status'] ?? '') === 'perdu' ? 'selected' : '' ?>>Perdu</option>
        </select>
        <button type="submit" class="btn btn-secondary">Filtrer</button>
        <a href="<?= rtrim(BASE_URL, '/') ?>/?page=loan" class="btn btn-link">Réinitialiser</a>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Radio</th>
                    <th>Emprunteur</th>
                    <th>Activité</th>
                    <th>Emprunté le</th>
                    <th>Retour prévu</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($loans)): ?>
                <tr>
                    <td colspan="7" class="text-center">Aucun emprunt trouvé</td>
                </tr>
                <?php else: ?>
                <?php foreach ($loans as $loan): ?>
                <tr class="<?= $loan['status'] === 'en_retard' ? 'row-warning' : '' ?>">
                    <td><strong><?= htmlspecialchars($loan['radio_code']) ?></strong></td>
                    <td><?= htmlspecialchars($loan['borrower_name']) ?><?= $loan['borrower_id'] ? ' (' . htmlspecialchars($loan['borrower_id']) . ')' : '' ?></td>
                    <td><?= htmlspecialchars($loan['activity_name']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($loan['borrowed_at'])) ?></td>
                    <td><?= $loan['due_at'] ? date('d/m/Y', strtotime($loan['due_at'])) : '-' ?></td>
                    <td>
                        <span class="badge badge-<?= $loan['status'] ?>">
                            <?= ucfirst(str_replace('_', ' ', $loan['status'])) ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($loan['status'] === 'en_cours' || $loan['status'] === 'en_retard'): ?>
                        <a href="<?= rtrim(BASE_URL, '/') ?>/?page=loan&action=return&id=<?= $loan['id'] ?>" class="btn btn-sm btn-success">↩️ Retour</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

