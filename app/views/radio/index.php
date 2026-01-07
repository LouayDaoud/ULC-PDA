<div class="page-header">
    <h1 class="page-title">Inventaire des radios</h1>
    <a href="<?= rtrim(BASE_URL, '/') ?>/?page=radio&action=create" class="btn btn-primary">➕ Ajouter une radio</a>
</div>

<div class="filters">
    <form method="GET" class="filter-form">
        <input type="hidden" name="page" value="radio">
        <input type="text" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>" class="input">
        <select name="status" class="select">
            <option value="">Tous les états</option>
            <option value="disponible" <?= ($filters['status'] ?? '') === 'disponible' ? 'selected' : '' ?>>Disponible</option>
            <option value="empruntee" <?= ($filters['status'] ?? '') === 'empruntee' ? 'selected' : '' ?>>Empruntée</option>
            <option value="reparation" <?= ($filters['status'] ?? '') === 'reparation' ? 'selected' : '' ?>>En réparation</option>
            <option value="rebut" <?= ($filters['status'] ?? '') === 'rebut' ? 'selected' : '' ?>>Rebut</option>
        </select>
        <select name="activity_id" class="select">
            <option value="">Toutes les activités</option>
            <?php foreach ($activities as $activity): ?>
            <option value="<?= $activity['id'] ?>" <?= ($filters['activity_id'] ?? '') == $activity['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($activity['name']) ?>
            </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-secondary">Filtrer</button>
        <a href="<?= rtrim(BASE_URL, '/') ?>/?page=radio" class="btn btn-link">Réinitialiser</a>
    </form>
</div>

<div class="stats-bar">
    <span>Total: <?= $stats['total'] ?></span>
    <span>Disponibles: <?= $stats['disponible'] ?></span>
    <span>Empruntées: <?= $stats['empruntee'] ?></span>
    <span>Réparation: <?= $stats['reparation'] ?></span>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>N° série</th>
                    <th>Modèle</th>
                    <th>État</th>
                    <th>Activité</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($radios)): ?>
                <tr>
                    <td colspan="6" class="text-center">Aucune radio trouvée</td>
                </tr>
                <?php else: ?>
                <?php foreach ($radios as $radio): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($radio['code']) ?></strong></td>
                    <td><?= htmlspecialchars($radio['serial_number'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($radio['model'] ?? '-') ?></td>
                    <td>
                        <span class="badge badge-<?= $radio['status'] ?>">
                            <?= ucfirst($radio['status']) ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($radio['activity_name'] ?? '-') ?></td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="<?= rtrim(BASE_URL, '/') ?>/?page=radio&action=edit&id=<?= $radio['id'] ?>" class="btn btn-sm btn-secondary">✏️</a>
                            <form method="POST" action="<?= rtrim(BASE_URL, '/') ?>/?page=radio&action=delete&id=<?= $radio['id'] ?>" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer la radio <?= htmlspecialchars($radio['code']) ?> ?');">
                                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                                <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

