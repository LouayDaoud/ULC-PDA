<?php
/**
 * Contrôleur pour l'authentification
 */

class LoginController extends BaseController {
    public function indexAction() {
        if (Auth::isLoggedIn()) {
            $this->redirect('/?page=dashboard');
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $error = "Veuillez remplir tous les champs";
            } elseif (Auth::login($username, $password)) {
                $this->redirect('/?page=dashboard');
            } else {
                $error = "Identifiants incorrects";
            }
        }

        $this->render('login/index', ['error' => $error]);
    }

    public function logoutAction() {
        AuditLog::logAction(
            Auth::getUserId(),
            'LOGOUT',
            'user',
            Auth::getUserId(),
            "Déconnexion"
        );
        Auth::logout();
    }
}

