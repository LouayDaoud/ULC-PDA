<?php
/**
 * Configuration générale de l'application
 */

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configuration de base
define('APP_NAME', 'ULC PDA - Gestion des Radios');
define('APP_VERSION', '1.0.0');
define('BASE_URL', '/ULC-PDA/');

// Chemin des dossiers
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('VIEWS_PATH', APP_PATH . '/views');
define('ASSETS_PATH', ROOT_PATH . '/assets');

// Configuration de sécurité
define('CSRF_TOKEN_NAME', 'csrf_token');
define('SESSION_TIMEOUT', 3600); // 1 heure

// Charger la configuration de la base de données
$dbConfig = require ROOT_PATH . '/config/database.php';

// Fonction pour obtenir l'adresse IP
function getClientIp() {
    $ip = $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    return $ip;
}

// Fonction pour générer un token CSRF
function generateCsrfToken() {
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

// Fonction pour vérifier le token CSRF
function verifyCsrfToken($token) {
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

