<div class="page-header">
    <h1 class="page-title">Journal d'audit</h1>
    <a href="<?= rtrim(BASE_URL, '/') ?>/?page=settings" class="btn btn-link">← Retour</a>
</div>

<div class="filters">
    <form method="GET" class="filter-form">
        <input type="hidden" name="page" value="settings">
        <input type="hidden" name="action" value="audit">
        <input type="text" name="action_type" placeholder="Type d'action" value="<?= htmlspecialchars($filters['action_type'] ?? '') ?>" class="input">
        <input type="text" name="entity_type" placeholder="Type d'entité" value="<?= htmlspecialchars($filters['entity_type'] ?? '') ?>" class="input">
        <input type="date" name="date_from" value="<?= htmlspecialchars($filters['date_from'] ?? '') ?>" class="input">
        <input type="date" name="date_to" value="<?= htmlspecialchars($filters['date_to'] ?? '') ?>" class="input">
        <button type="submit" class="btn btn-secondary">Filtrer</button>
        <a href="<?= rtrim(BASE_URL, '/') ?>/?page=settings&action=audit" class="btn btn-link">Réinitialiser</a>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Date/Heure</th>
                    <th>Utilisateur</th>
                    <th>Action</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                <tr>
                    <td colspan="6" class="text-center">Aucun log trouvé</td>
                </tr>
                <?php else: ?>
                <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?></td>
                    <td><?= htmlspecialchars($log['username'] ?? '-') ?></td>
                    <td><code><?= htmlspecialchars($log['action_type']) ?></code></td>
                    <td><?= htmlspecialchars($log['entity_type']) ?></td>
                    <td><?= htmlspecialchars($log['description']) ?></td>
                    <td><?= htmlspecialchars($log['ip_address'] ?? '-') ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

