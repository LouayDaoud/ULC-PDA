<?php
/**
 * Contrôleur pour le tableau de bord
 */

class DashboardController extends BaseController {
    public function indexAction() {
        $radioModel = new Radio();
        $loanModel = new Loan();
        $maintenanceModel = new Maintenance();
        $activityModel = new Activity();

        // Mettre à jour les emprunts en retard
        $loanModel->updateOverdueStatus();

        $data = [
            'radioStats' => $radioModel->getStats(),
            'loanStats' => $loanModel->getStats(),
            'maintenanceStats' => $maintenanceModel->getStats(),
            'overdueLoans' => $loanModel->getOverdue(),
            'activeMaintenances' => $maintenanceModel->getActive(),
            'activities' => $activityModel->getAll()
        ];

        $this->render('dashboard/index', $data);
    }
}

