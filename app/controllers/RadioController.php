<?php
/**
 * Contrôleur pour la gestion des radios
 */

class RadioController extends BaseController {
    public function indexAction() {
        $model = new Radio();
        $activityModel = new Activity();

        $filters = [
            'status' => $_GET['status'] ?? null,
            'activity_id' => $_GET['activity_id'] ?? null,
            'search' => $_GET['search'] ?? null
        ];

        $data = [
            'radios' => $model->getAll($filters),
            'activities' => $activityModel->getAll(),
            'filters' => $filters,
            'stats' => $model->getStats()
        ];

        $this->render('radio/index', $data);
    }

    public function createAction() {
        $model = new Radio();
        $activityModel = new Activity();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'code' => trim($_POST['code'] ?? ''),
                'serial_number' => trim($_POST['serial_number'] ?? ''),
                'model' => trim($_POST['model'] ?? ''),
                'status' => $_POST['status'] ?? 'disponible',
                'activity_id' => !empty($_POST['activity_id']) ? (int)$_POST['activity_id'] : null,
                'comments' => trim($_POST['comments'] ?? '')
            ];

            if (empty($data['code'])) {
                $error = "Le code est obligatoire";
            } elseif ($model->getByCode($data['code'])) {
                $error = "Ce code existe déjà";
            } else {
                try {
                    $model->create($data);
                    $this->redirect('/?page=radio&action=index');
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }

        $this->render('radio/form', [
            'radio' => null,
            'activities' => $activityModel->getAll(),
            'error' => $error
        ]);
    }

    public function editAction() {
        $model = new Radio();
        $activityModel = new Activity();
        $id = (int)($_GET['id'] ?? 0);
        $radio = $model->getById($id);
        $error = null;

        if (!$radio) {
            $this->redirect('/?page=radio&action=index');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'code' => trim($_POST['code'] ?? ''),
                'serial_number' => trim($_POST['serial_number'] ?? ''),
                'model' => trim($_POST['model'] ?? ''),
                'status' => $_POST['status'] ?? 'disponible',
                'activity_id' => !empty($_POST['activity_id']) ? (int)$_POST['activity_id'] : null,
                'comments' => trim($_POST['comments'] ?? '')
            ];

            if (empty($data['code'])) {
                $error = "Le code est obligatoire";
            } else {
                $existing = $model->getByCode($data['code']);
                if ($existing && $existing['id'] != $id) {
                    $error = "Ce code existe déjà";
                } else {
                    try {
                        $model->update($id, $data);
                        $this->redirect('/?page=radio&action=index');
                    } catch (Exception $e) {
                        $error = $e->getMessage();
                    }
                }
            }
        }

        $this->render('radio/form', [
            'radio' => $radio,
            'activities' => $activityModel->getAll(),
            'error' => $error
        ]);
    }

    public function deleteAction() {
        $model = new Radio();
        $id = (int)($_GET['id'] ?? 0);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            try {
                $model->delete($id);
            } catch (Exception $e) {
                // Erreur silencieuse ou log
            }
        }
        
        $this->redirect('/?page=radio&action=index');
    }
}

