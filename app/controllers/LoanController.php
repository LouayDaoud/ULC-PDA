<?php
/**
 * Contrôleur pour la gestion des emprunts
 */

class LoanController extends BaseController {
    public function indexAction() {
        $model = new Loan();
        $activityModel = new Activity();
        $radioModel = new Radio();

        // Mettre à jour les emprunts en retard
        $model->updateOverdueStatus();

        $filters = [
            'status' => $_GET['status'] ?? null,
            'activity_id' => $_GET['activity_id'] ?? null,
            'search' => $_GET['search'] ?? null
        ];

        $data = [
            'loans' => $model->getAll($filters),
            'activities' => $activityModel->getAll(),
            'filters' => $filters,
            'stats' => $model->getStats(),
            'overdue' => $model->getOverdue()
        ];

        $this->render('loan/index', $data);
    }

    public function createAction() {
        $model = new Loan();
        $radioModel = new Radio();
        $activityModel = new Activity();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'radio_id' => (int)($_POST['radio_id'] ?? 0),
                'borrower_name' => trim($_POST['borrower_name'] ?? ''),
                'borrower_id' => trim($_POST['borrower_id'] ?? ''),
                'activity_id' => (int)($_POST['activity_id'] ?? 0),
                'due_at' => !empty($_POST['due_at']) ? $_POST['due_at'] : null,
                'state_out' => trim($_POST['state_out'] ?? ''),
                'comments' => trim($_POST['comments'] ?? '')
            ];

            if (empty($data['radio_id']) || empty($data['borrower_name']) || empty($data['activity_id'])) {
                $error = "Veuillez remplir tous les champs obligatoires";
            } else {
                try {
                    $model->create($data);
                    $this->redirect('/?page=loan&action=index');
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }

        $data = [
            'availableRadios' => $radioModel->getAll(['status' => 'disponible']),
            'activities' => $activityModel->getAll(),
            'error' => $error
        ];

        $this->render('loan/form', $data);
    }

    public function returnAction() {
        $model = new Loan();
        $id = (int)($_GET['id'] ?? 0);
        $loan = $model->getById($id);
        $error = null;

        if (!$loan) {
            $this->redirect('/?page=loan&action=index');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'state_in' => trim($_POST['state_in'] ?? ''),
                'comments' => trim($_POST['comments'] ?? '')
            ];

            try {
                $model->returnLoan($id, $data);
                $this->redirect('/?page=loan&action=index');
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        $this->render('loan/return', ['loan' => $loan, 'error' => $error]);
    }

    public function lostAction() {
        $model = new Loan();
        $id = (int)($_GET['id'] ?? 0);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            try {
                $model->markAsLost($id);
            } catch (Exception $e) {
                // Erreur
            }
        }
        
        $this->redirect('/?page=loan&action=index');
    }
}

