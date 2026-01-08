<div class="page-header">
    <h1 class="page-title">Radios disponibles - <?= htmlspecialchars($activity['name']) ?></h1>
    <a href="<?= rtrim(BASE_URL, '/') ?>/?page=activity" class="btn btn-link">← Retour aux activités</a>
</div>

<div class="info-box">
    <p><strong>Activité :</strong> <?= htmlspecialchars($activity['name']) ?></p>
    <?php if ($activity['description']): ?>
    <p><strong>Description :</strong> <?= htmlspecialchars($activity['description']) ?></p>
    <?php endif; ?>
    <p><strong>Nombre de radios disponibles :</strong> <?= count($radios) ?></p>
</div>

<?php if (empty($radios)): ?>
<div class="card">
    <div class="text-center" style="padding: 2rem;">
        <p style="font-size: 1.2rem; color: #666;">Aucune radio disponible pour cette activité</p>
    </div>
</div>
<?php else: ?>
<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Numéro de série</th>
                    <th>Modèle</th>
                    <th>État</th>
                    <th>Commentaires</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
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
                    <td><?= htmlspecialchars($radio['comments'] ?? '-') ?></td>
                    <td>
                        <a href="<?= rtrim(BASE_URL, '/') ?>/?page=radio&action=edit&id=<?= $radio['id'] ?>" class="btn btn-sm btn-secondary">✏️ Modifier</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>



