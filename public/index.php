<?php
/**
 * Point d'entrée de l'application
 */

// Charger la configuration
require_once dirname(__DIR__) . '/config/config.php';

// Charger les classes core
require_once APP_PATH . '/core/Database.php';
require_once APP_PATH . '/core/Auth.php';
require_once APP_PATH . '/core/AuditLog.php';
require_once APP_PATH . '/core/Router.php';

// Charger les modèles
require_once APP_PATH . '/models/Radio.php';
require_once APP_PATH . '/models/Activity.php';
require_once APP_PATH . '/models/Loan.php';
require_once APP_PATH . '/models/Maintenance.php';

// Charger le routeur
$router = new Router();
$router->dispatch();

