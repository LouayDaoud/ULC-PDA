<div class="page-header">
    <h1 class="page-title">Rapports & Statistiques</h1>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Radios totales</div>
        <div class="stat-value"><?= $radioStats['total'] ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Emprunts totaux</div>
        <div class="stat-value"><?= $loanStats['total'] ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Emprunts en cours</div>
        <div class="stat-value"><?= $loanStats['en_cours'] ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">En retard</div>
        <div class="stat-value"><?= $loanStats['en_retard'] ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Maintenances actives</div>
        <div class="stat-value"><?= $maintenanceStats['en_attente'] + $maintenanceStats['diagnostic'] + $maintenanceStats['reparation'] + $maintenanceStats['test'] ?></div>
    </div>
</div>

<div class="section">
    <h2 class="section-title">Radios les plus utilisées</h2>
    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>Radio</th>
                    <th>Nombre d'emprunts</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mostUsedRadios as $radio): ?>
                <tr>
                    <td><?= htmlspecialchars($radio['code']) ?></td>
                    <td><?= $radio['loan_count'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="section">
    <h2 class="section-title">Radios les plus souvent en panne</h2>
    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>Radio</th>
                    <th>Nombre de maintenances</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mostProblematicRadios as $radio): ?>
                <tr>
                    <td><?= htmlspecialchars($radio['code']) ?></td>
                    <td><?= $radio['maintenance_count'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="section">
    <h2 class="section-title">Exports</h2>
    <div class="card">
        <div class="export-buttons">
            <a href="<?= rtrim(BASE_URL, '/') ?>/?page=report&action=export&type=radios" class="btn btn-secondary">📥 Exporter les radios (CSV)</a>
            <a href="<?= rtrim(BASE_URL, '/') ?>/?page=report&action=export&type=loans" class="btn btn-secondary">📥 Exporter les emprunts (CSV)</a>
            <a href="<?= rtrim(BASE_URL, '/') ?>/?page=report&action=export&type=activities" class="btn btn-secondary">📥 Exporter les activités (CSV)</a>
            <a href="<?= rtrim(BASE_URL, '/') ?>/?page=report&action=export&type=maintenances" class="btn btn-secondary">📥 Exporter les maintenances (CSV)</a>
            <a href="<?= rtrim(BASE_URL, '/') ?>/?page=report&action=export&type=audit" class="btn btn-secondary">📥 Exporter l'audit (CSV)</a>
        </div>
    </div>
</div>

