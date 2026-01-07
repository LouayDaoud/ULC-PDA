<div class="page-header">
    <h1 class="page-title">Historique des connexions</h1>
    <a href="<?= rtrim(BASE_URL, '/') ?>/?page=settings" class="btn btn-link">← Retour</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Date/Heure</th>
                    <th>Utilisateur</th>
                    <th>IP</th>
                    <th>Résultat</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($history)): ?>
                <tr>
                    <td colspan="4" class="text-center">Aucun historique</td>
                </tr>
                <?php else: ?>
                <?php foreach ($history as $entry): ?>
                <tr class="<?= $entry['success'] ? '' : 'row-error' ?>">
                    <td><?= date('d/m/Y H:i:s', strtotime($entry['login_at'])) ?></td>
                    <td><?= htmlspecialchars($entry['username'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($entry['ip_address'] ?? '-') ?></td>
                    <td>
                        <?php if ($entry['success']): ?>
                        <span class="badge badge-success">Succès</span>
                        <?php else: ?>
                        <span class="badge badge-error">Échec</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

