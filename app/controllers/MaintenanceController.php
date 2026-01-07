<?php
/**
 * Contrôleur pour la gestion des maintenances
 */

class MaintenanceController extends BaseController {
    public function indexAction() {
        $model = new Maintenance();
        $radioModel = new Radio();

        $filters = [
            'status' => $_GET['status'] ?? null,
            'radio_id' => !empty($_GET['radio_id']) ? (int)$_GET['radio_id'] : null
        ];

        $data = [
            'maintenances' => $model->getAll($filters),
            'filters' => $filters,
            'stats' => $model->getStats(),
            'active' => $model->getActive()
        ];

        $this->render('maintenance/index', $data);
    }

    public function createAction() {
        $model = new Maintenance();
        $radioModel = new Radio();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'radio_id' => (int)($_POST['radio_id'] ?? 0),
                'reported_by' => trim($_POST['reported_by'] ?? ''),
                'issue_type' => trim($_POST['issue_type'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'status' => $_POST['status'] ?? 'en_attente',
                'comments' => trim($_POST['comments'] ?? '')
            ];

            if (empty($data['radio_id']) || empty($data['reported_by']) || empty($data['issue_type'])) {
                $error = "Veuillez remplir tous les champs obligatoires";
            } else {
                try {
                    $model->create($data);
                    $this->redirect('/?page=maintenance&action=index');
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }

        $data = [
            'radios' => $radioModel->getAll(),
            'error' => $error
        ];

        $this->render('maintenance/form', $data);
    }

    public function editAction() {
        $model = new Maintenance();
        $id = (int)($_GET['id'] ?? 0);
        $maintenance = $model->getById($id);
        $error = null;

        if (!$maintenance) {
            $this->redirect('/?page=maintenance&action=index');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'issue_type' => trim($_POST['issue_type'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'status' => $_POST['status'] ?? 'en_attente',
                'comments' => trim($_POST['comments'] ?? '')
            ];

            try {
                $model->update($id, $data);
                $this->redirect('/?page=maintenance&action=index');
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        $this->render('maintenance/form', [
            'maintenance' => $maintenance,
            'radios' => (new Radio())->getAll(),
            'error' => $error
        ]);
    }
}

