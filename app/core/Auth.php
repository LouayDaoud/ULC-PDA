<?php
/**
 * Gestion de l'authentification
 */

class Auth {
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']) && isset($_SESSION['username']);
    }

    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: ' . rtrim(BASE_URL, '/') . '/?page=login');
            exit;
        }
    }

    public static function login($username, $password) {
        $db = Database::getInstance();
        $user = $db->fetchOne(
            "SELECT * FROM users WHERE username = ?",
            [$username]
        );

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            // Mettre à jour la dernière connexion
            $db->query(
                "UPDATE users SET last_login_at = NOW() WHERE id = ?",
                [$user['id']]
            );

            // Enregistrer dans l'historique
            AuditLog::logLogin($user['id'], true);
            
            return true;
        }

        // Enregistrer l'échec de connexion (seulement si l'utilisateur existe)
        if ($user) {
            AuditLog::logLogin($user['id'], false);
        } else {
            // Enregistrer une tentative de connexion avec un utilisateur inexistant
            AuditLog::logLogin(null, false);
        }
        
        return false;
    }

    public static function logout() {
        session_destroy();
        header('Location: ' . rtrim(BASE_URL, '/') . '/?page=login');
        exit;
    }

    public static function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }

    public static function getUsername() {
        return $_SESSION['username'] ?? null;
    }

    public static function changePassword($userId, $oldPassword, $newPassword) {
        $db = Database::getInstance();
        $user = $db->fetchOne("SELECT * FROM users WHERE id = ?", [$userId]);

        if (!$user || !password_verify($oldPassword, $user['password_hash'])) {
            return false;
        }

        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $db->query(
            "UPDATE users SET password_hash = ? WHERE id = ?",
            [$newHash, $userId]
        );

        AuditLog::logAction(
            $userId,
            'CHANGE_PASSWORD',
            'user',
            $userId,
            "Changement de mot de passe"
        );

        return true;
    }
}

