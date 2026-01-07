<div class="page-header">
    <h1 class="page-title">Maintenance & Réparations</h1>
    <a href="<?= rtrim(BASE_URL, '/') ?>/?page=maintenance&action=create" class="btn btn-primary">➕ Nouvelle maintenance</a>
</div>

<div class="filters">
    <form method="GET" class="filter-form">
        <input type="hidden" name="page" value="maintenance">
        <select name="status" class="select">
            <option value="">Tous les statuts</option>
            <option value="en_attente" <?= ($filters['status'] ?? '') === 'en_attente' ? 'selected' : '' ?>>En attente</option>
            <option value="diagnostic" <?= ($filters['status'] ?? '') === 'diagnostic' ? 'selected' : '' ?>>En diagnostic</option>
            <option value="reparation" <?= ($filters['status'] ?? '') === 'reparation' ? 'selected' : '' ?>>En réparation</option>
            <option value="test" <?= ($filters['status'] ?? '') === 'test' ? 'selected' : '' ?>>Test</option>
            <option value="reparee" <?= ($filters['status'] ?? '') === 'reparee' ? 'selected' : '' ?>>Réparée</option>
        </select>
        <button type="submit" class="btn btn-secondary">Filtrer</button>
        <a href="<?= rtrim(BASE_URL, '/') ?>/?page=maintenance" class="btn btn-link">Réinitialiser</a>
    </form>
</div>

<?php if (!empty($active)): ?>
<div class="alert alert-info">
    <strong>ℹ️ <?= count($active) ?> maintenance(s) active(s)</strong>
</div>
<?php endif; ?>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Radio</th>
                    <th>Signalé par</th>
                    <th>Type de panne</th>
                    <th>Description</th>
                    <th>Statut</th>
                    <th>Signalé le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($maintenances)): ?>
                <tr>
                    <td colspan="7" class="text-center">Aucune maintenance</td>
                </tr>
                <?php else: ?>
                <?php foreach ($maintenances as $maint): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($maint['radio_code']) ?></strong></td>
                    <td><?= htmlspecialchars($maint['reported_by']) ?></td>
                    <td><?= htmlspecialchars($maint['issue_type']) ?></td>
                    <td><?= htmlspecialchars(mb_substr($maint['description'] ?? '', 0, 50)) ?><?= mb_strlen($maint['description'] ?? '') > 50 ? '...' : '' ?></td>
                    <td>
                        <span class="badge badge-<?= $maint['status'] ?>">
                            <?= ucfirst(str_replace('_', ' ', $maint['status'])) ?>
                        </span>
                    </td>
                    <td><?= date('d/m/Y', strtotime($maint['reported_at'])) ?></td>
                    <td>
                        <a href="<?= rtrim(BASE_URL, '/') ?>/?page=maintenance&action=edit&id=<?= $maint['id'] ?>" class="btn btn-sm btn-secondary">✏️</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

