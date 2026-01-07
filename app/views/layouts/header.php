<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= rtrim(BASE_URL, '/') ?>/assets/css/style.css">
</head>
<body>
    <?php if (Auth::isLoggedIn()): ?>
    <nav class="main-nav">
        <div class="nav-brand"><?= APP_NAME ?></div>
        <div class="nav-user"><?= htmlspecialchars(Auth::getUsername()) ?></div>
    </nav>
    <nav class="menu-nav">
        <a href="<?= rtrim(BASE_URL, '/') ?>/?page=dashboard" class="menu-item <?= ($_GET['page'] ?? '') === 'dashboard' ? 'active' : '' ?>">
            <span class="menu-icon">📊</span>
            <span class="menu-text">Tableau de bord</span>
        </a>
        <a href="<?= rtrim(BASE_URL, '/') ?>/?page=radio" class="menu-item <?= ($_GET['page'] ?? '') === 'radio' ? 'active' : '' ?>">
            <span class="menu-icon">📻</span>
            <span class="menu-text">Radios</span>
        </a>
        <a href="<?= rtrim(BASE_URL, '/') ?>/?page=loan" class="menu-item <?= ($_GET['page'] ?? '') === 'loan' ? 'active' : '' ?>">
            <span class="menu-icon">📤</span>
            <span class="menu-text">Emprunts</span>
        </a>
        <a href="<?= rtrim(BASE_URL, '/') ?>/?page=activity" class="menu-item <?= ($_GET['page'] ?? '') === 'activity' ? 'active' : '' ?>">
            <span class="menu-icon">🏢</span>
            <span class="menu-text">Activités</span>
        </a>
        <a href="<?= rtrim(BASE_URL, '/') ?>/?page=maintenance" class="menu-item <?= ($_GET['page'] ?? '') === 'maintenance' ? 'active' : '' ?>">
            <span class="menu-icon">🔧</span>
            <span class="menu-text">Maintenance</span>
        </a>
        <a href="<?= rtrim(BASE_URL, '/') ?>/?page=report" class="menu-item <?= ($_GET['page'] ?? '') === 'report' ? 'active' : '' ?>">
            <span class="menu-icon">📈</span>
            <span class="menu-text">Rapports</span>
        </a>
        <a href="<?= rtrim(BASE_URL, '/') ?>/?page=settings" class="menu-item <?= ($_GET['page'] ?? '') === 'settings' ? 'active' : '' ?>">
            <span class="menu-icon">⚙️</span>
            <span class="menu-text">Paramètres</span>
        </a>
        <a href="<?= rtrim(BASE_URL, '/') ?>/?page=login&action=logout" class="menu-item">
            <span class="menu-icon">🚪</span>
            <span class="menu-text">Déconnexion</span>
        </a>
    </nav>
    <?php endif; ?>
    <main class="main-content">
        <?php if (isset($error) && $error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if (isset($success) && $success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

