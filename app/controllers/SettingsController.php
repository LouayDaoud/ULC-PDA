<?php
/**
 * Contrôleur pour les paramètres
 */

class SettingsController extends BaseController {
    public function indexAction() {
        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
            $oldPassword = $_POST['old_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
                $error = "Veuillez remplir tous les champs";
            } elseif ($newPassword !== $confirmPassword) {
                $error = "Les mots de passe ne correspondent pas";
            } elseif (strlen($newPassword) < 6) {
                $error = "Le mot de passe doit contenir au moins 6 caractères";
            } else {
                if (Auth::changePassword(Auth::getUserId(), $oldPassword, $newPassword)) {
                    $success = "Mot de passe modifié avec succès";
                } else {
                    $error = "Ancien mot de passe incorrect";
                }
            }
        }

        $this->render('settings/index', ['error' => $error, 'success' => $success]);
    }

    public function auditAction() {
        $filters = [
            'action_type' => $_GET['action_type'] ?? null,
            'entity_type' => $_GET['entity_type'] ?? null,
            'date_from' => $_GET['date_from'] ?? null,
            'date_to' => $_GET['date_to'] ?? null
        ];

        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 50;
        $offset = ($page - 1) * $limit;

        $logs = AuditLog::getLogs($limit, $offset, $filters);
        $total = count(AuditLog::getLogs(10000, 0, $filters));

        $this->render('settings/audit', [
            'logs' => $logs,
            'filters' => $filters,
            'page' => $page,
            'total' => $total,
            'totalPages' => ceil($total / $limit)
        ]);
    }

    public function loginHistoryAction() {
        $history = AuditLog::getLoginHistory(100);
        $this->render('settings/login_history', ['history' => $history]);
    }
}

