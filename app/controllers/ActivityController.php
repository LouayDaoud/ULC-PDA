<?php
/**
 * Contrôleur pour la gestion des activités
 */

class ActivityController extends BaseController {
    public function indexAction() {
        $model = new Activity();
        $data = [
            'activities' => $model->getAll()
        ];
        $this->render('activity/index', $data);
    }

    public function createAction() {
        $model = new Activity();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? '')
            ];

            if (empty($data['name'])) {
                $error = "Le nom est obligatoire";
            } else {
                try {
                    $model->create($data);
                    $this->redirect('/?page=activity&action=index');
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }

        $this->render('activity/form', ['activity' => null, 'error' => $error]);
    }

    public function editAction() {
        $model = new Activity();
        $id = (int)($_GET['id'] ?? 0);
        $activity = $model->getById($id);
        $error = null;

        if (!$activity) {
            $this->redirect('/?page=activity&action=index');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? '')
            ];

            if (empty($data['name'])) {
                $error = "Le nom est obligatoire";
            } else {
                try {
                    $model->update($id, $data);
                    $this->redirect('/?page=activity&action=index');
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }

        $this->render('activity/form', ['activity' => $activity, 'error' => $error]);
    }

    public function deleteAction() {
        $model = new Activity();
        $id = (int)($_GET['id'] ?? 0);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            try {
                $model->delete($id);
            } catch (Exception $e) {
                // Erreur
            }
        }
        
        $this->redirect('/?page=activity&action=index');
    }

    public function radiosAction() {
        $activityModel = new Activity();
        $id = (int)($_GET['id'] ?? 0);
        $activity = $activityModel->getById($id);

        if (!$activity) {
            $this->redirect('/?page=activity&action=index');
        }

        $availableRadios = $activityModel->getAvailableRadios($id);

        $this->render('activity/radios', [
            'activity' => $activity,
            'radios' => $availableRadios
        ]);
    }
}

