<div class="page-header">
    <h1 class="page-title">Activités</h1>
    <a href="<?= rtrim(BASE_URL, '/') ?>/?page=activity&action=create" class="btn btn-primary">➕ Ajouter une activité</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Total radios</th>
                    <th>Disponibles</th>
                    <th>Empruntées</th>
                    <th>En réparation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($activities)): ?>
                <tr>
                    <td colspan="7" class="text-center">Aucune activité</td>
                </tr>
                <?php else: ?>
                <?php foreach ($activities as $activity): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($activity['name']) ?></strong></td>
                    <td><?= htmlspecialchars($activity['description'] ?? '-') ?></td>
                    <td><?= $activity['total_radios'] ?></td>
                    <td>
                        <?php if ($activity['radios_disponibles'] > 0): ?>
                            <a href="<?= rtrim(BASE_URL, '/') ?>/?page=activity&action=radios&id=<?= $activity['id'] ?>" class="btn btn-sm btn-success" title="Voir les radios disponibles">
                                <?= $activity['radios_disponibles'] ?>
                            </a>
                        <?php else: ?>
                            <?= $activity['radios_disponibles'] ?>
                        <?php endif; ?>
                    </td>
                    <td><?= $activity['radios_empruntees'] ?></td>
                    <td><?= $activity['radios_reparation'] ?></td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="<?= rtrim(BASE_URL, '/') ?>/?page=activity&action=edit&id=<?= $activity['id'] ?>" class="btn btn-sm btn-secondary">✏️</a>
                            <?php if ($activity['radios_disponibles'] > 0): ?>
                                <a href="<?= rtrim(BASE_URL, '/') ?>/?page=activity&action=radios&id=<?= $activity['id'] ?>" class="btn btn-sm btn-primary" title="Voir les détails">📻</a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

