<div class="dashboard">
    <h1 class="page-title">Tableau de bord</h1>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📻</div>
            <div class="stat-content">
                <div class="stat-label">Radios totales</div>
                <div class="stat-value"><?= $radioStats['total'] ?></div>
            </div>
        </div>
        <div class="stat-card stat-available">
            <div class="stat-icon">✅</div>
            <div class="stat-content">
                <div class="stat-label">Disponibles</div>
                <div class="stat-value"><?= $radioStats['disponible'] ?></div>
            </div>
        </div>
        <div class="stat-card stat-borrowed">
            <div class="stat-icon">📤</div>
            <div class="stat-content">
                <div class="stat-label">Empruntées</div>
                <div class="stat-value"><?= $radioStats['empruntee'] ?></div>
            </div>
        </div>
        <div class="stat-card stat-repair">
            <div class="stat-icon">🔧</div>
            <div class="stat-content">
                <div class="stat-label">En réparation</div>
                <div class="stat-value"><?= $radioStats['reparation'] ?></div>
            </div>
        </div>
    </div>

    <?php if (!empty($overdueLoans)): ?>
    <div class="alert-section">
        <h2 class="section-title">⚠️ Emprunts en retard</h2>
        <div class="card">
            <ul class="list">
                <?php foreach ($overdueLoans as $loan): ?>
                <li class="list-item">
                    <strong><?= htmlspecialchars($loan['radio_code']) ?></strong> - 
                    <?= htmlspecialchars($loan['borrower_name']) ?> - 
                    Retour prévu: <?= date('d/m/Y', strtotime($loan['due_at'])) ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($activeMaintenances)): ?>
    <div class="alert-section">
        <h2 class="section-title">🔧 Maintenances en cours</h2>
        <div class="card">
            <ul class="list">
                <?php foreach (array_slice($activeMaintenances, 0, 5) as $maint): ?>
                <li class="list-item">
                    <strong><?= htmlspecialchars($maint['radio_code']) ?></strong> - 
                    <?= htmlspecialchars($maint['issue_type']) ?> 
                    (<?= htmlspecialchars($maint['status']) ?>)
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php endif; ?>

    <div class="section">
        <h2 class="section-title">Activités</h2>
        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>Activité</th>
                        <th>Total</th>
                        <th>Disponibles</th>
                        <th>Empruntées</th>
                        <th>Réparation</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activities as $activity): ?>
                    <tr>
                        <td><?= htmlspecialchars($activity['name']) ?></td>
                        <td><?= $activity['total_radios'] ?></td>
                        <td><?= $activity['radios_disponibles'] ?></td>
                        <td><?= $activity['radios_empruntees'] ?></td>
                        <td><?= $activity['radios_reparation'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

