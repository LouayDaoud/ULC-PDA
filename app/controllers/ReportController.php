<?php
/**
 * Contrôleur pour les rapports et statistiques
 */

class ReportController extends BaseController {
    public function indexAction() {
        $radioModel = new Radio();
        $loanModel = new Loan();
        $maintenanceModel = new Maintenance();
        $activityModel = new Activity();

        $data = [
            'radioStats' => $radioModel->getStats(),
            'loanStats' => $loanModel->getStats(),
            'maintenanceStats' => $maintenanceModel->getStats(),
            'activities' => $activityModel->getAll(),
            'overdueLoans' => $loanModel->getOverdue(),
            'mostUsedRadios' => $this->getMostUsedRadios(),
            'mostProblematicRadios' => $this->getMostProblematicRadios()
        ];

        $this->render('report/index', $data);
    }

    public function exportAction() {
        $type = $_GET['type'] ?? 'radios';
        $db = Database::getInstance();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="export_' . $type . '_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
        
        // BOM UTF-8 pour Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        switch ($type) {
            case 'radios':
                $this->exportRadios($output, $db);
                break;
            case 'loans':
                $this->exportLoans($output, $db);
                break;
            case 'activities':
                $this->exportActivities($output, $db);
                break;
            case 'maintenances':
                $this->exportMaintenances($output, $db);
                break;
            case 'audit':
                $this->exportAudit($output, $db);
                break;
        }

        fclose($output);
        exit;
    }

    private function exportRadios($output, $db) {
        fputcsv($output, ['Code', 'Numéro de série', 'Adresse MAC', 'Modèle', 'État', 'Activité', 'Commentaires', 'Créé le']);
        $radios = $db->fetchAll(
            "SELECT r.*, a.name as activity_name 
             FROM radios r 
             LEFT JOIN activities a ON r.activity_id = a.id 
             ORDER BY r.code"
        );
        foreach ($radios as $radio) {
            fputcsv($output, [
                $radio['code'],
                $radio['serial_number'] ?? '',
                $radio['mac_address'] ?? '',
                $radio['model'] ?? '',
                $radio['status'],
                $radio['activity_name'] ?? '',
                $radio['comments'] ?? '',
                $radio['created_at']
            ]);
        }
    }

    private function exportLoans($output, $db) {
        fputcsv($output, ['Radio', 'Emprunteur', 'Matricule', 'Activité', 'Emprunté le', 'Retour prévu', 'Retourné le', 'État']);
        $loans = $db->fetchAll(
            "SELECT l.*, r.code as radio_code, a.name as activity_name 
             FROM loans l 
             JOIN radios r ON l.radio_id = r.id 
             JOIN activities a ON l.activity_id = a.id 
             ORDER BY l.borrowed_at DESC"
        );
        foreach ($loans as $loan) {
            fputcsv($output, [
                $loan['radio_code'],
                $loan['borrower_name'],
                $loan['borrower_id'] ?? '',
                $loan['activity_name'],
                $loan['borrowed_at'],
                $loan['due_at'] ?? '',
                $loan['returned_at'] ?? '',
                $loan['status']
            ]);
        }
    }

    private function exportActivities($output, $db) {
        fputcsv($output, ['Nom', 'Description', 'Créé le']);
        $activities = $db->fetchAll("SELECT * FROM activities ORDER BY name");
        foreach ($activities as $activity) {
            fputcsv($output, [
                $activity['name'],
                $activity['description'] ?? '',
                $activity['created_at']
            ]);
        }
    }

    private function exportMaintenances($output, $db) {
        fputcsv($output, ['Radio', 'Signalé par', 'Type de panne', 'Description', 'État', 'Signalé le', 'Fermé le']);
        $maintenances = $db->fetchAll(
            "SELECT m.*, r.code as radio_code 
             FROM maintenances m 
             JOIN radios r ON m.radio_id = r.id 
             ORDER BY m.reported_at DESC"
        );
        foreach ($maintenances as $m) {
            fputcsv($output, [
                $m['radio_code'],
                $m['reported_by'],
                $m['issue_type'],
                $m['description'] ?? '',
                $m['status'],
                $m['reported_at'],
                $m['closed_at'] ?? ''
            ]);
        }
    }

    private function exportAudit($output, $db) {
        fputcsv($output, ['Date', 'Utilisateur', 'Action', 'Type', 'ID', 'Description', 'IP']);
        $logs = AuditLog::getLogs(10000);
        foreach ($logs as $log) {
            fputcsv($output, [
                $log['created_at'],
                $log['username'] ?? '',
                $log['action_type'],
                $log['entity_type'],
                $log['entity_id'] ?? '',
                $log['description'],
                $log['ip_address'] ?? ''
            ]);
        }
    }

    private function getMostUsedRadios() {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT r.code, COUNT(l.id) as loan_count 
             FROM radios r 
             LEFT JOIN loans l ON r.id = l.radio_id 
             GROUP BY r.id, r.code 
             ORDER BY loan_count DESC 
             LIMIT 10"
        );
    }

    private function getMostProblematicRadios() {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT r.code, COUNT(m.id) as maintenance_count 
             FROM radios r 
             LEFT JOIN maintenances m ON r.id = m.radio_id 
             GROUP BY r.id, r.code 
             ORDER BY maintenance_count DESC 
             LIMIT 10"
        );
    }
}

