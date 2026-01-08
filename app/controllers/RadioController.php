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
                'mac_address' => trim($_POST['mac_address'] ?? ''),
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
                'mac_address' => trim($_POST['mac_address'] ?? ''),
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

    public function exportExcelAction() {
        $model = new Radio();
        $filters = [
            'status' => $_GET['status'] ?? null,
            'activity_id' => $_GET['activity_id'] ?? null,
            'search' => $_GET['search'] ?? null
        ];
        
        $radios = $model->getAll($filters);
        
        // Générer le fichier Excel au format XML (SpreadsheetML)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="Inventaire_Radios_' . date('Y-m-d') . '.xls"');
        header('Cache-Control: max-age=0');
        
        // Convertir le statut pour correspondre à Excel
        $statusMap = [
            'disponible' => 'Actif',
            'empruntee' => 'Empruntée',
            'reparation' => 'En réparation',
            'rebut' => 'Rebut'
        ];
        
        // Générer le contenu Excel en XML
        echo '<?xml version="1.0"?>' . "\n";
        echo '<?mso-application progid="Excel.Sheet"?>' . "\n";
        echo '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"' . "\n";
        echo ' xmlns:o="urn:schemas-microsoft-com:office:office"' . "\n";
        echo ' xmlns:x="urn:schemas-microsoft-com:office:excel"' . "\n";
        echo ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"' . "\n";
        echo ' xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n";
        echo '<Worksheet ss:Name="Inventaire">' . "\n";
        echo '<Table>' . "\n";
        
        // En-têtes
        echo '<Row>' . "\n";
        echo '<Cell><Data ss:Type="String">Modèle</Data></Cell>' . "\n";
        echo '<Cell><Data ss:Type="String">Nom</Data></Cell>' . "\n";
        echo '<Cell><Data ss:Type="String">Statut</Data></Cell>' . "\n";
        echo '<Cell><Data ss:Type="String">SN</Data></Cell>' . "\n";
        echo '<Cell><Data ss:Type="String">Adresse MAC</Data></Cell>' . "\n";
        echo '<Cell><Data ss:Type="String">Emplacement / Activité</Data></Cell>' . "\n";
        echo '</Row>' . "\n";
        
        // Données
        foreach ($radios as $radio) {
            $model = htmlspecialchars($radio['model'] ?? '', ENT_XML1);
            $code = htmlspecialchars($radio['code'], ENT_XML1);
            $status = htmlspecialchars($statusMap[$radio['status']] ?? $radio['status'], ENT_XML1);
            $serial = htmlspecialchars($radio['serial_number'] ?? '', ENT_XML1);
            $activity = htmlspecialchars($radio['activity_name'] ?? '', ENT_XML1);
            
            echo '<Row>' . "\n";
            echo '<Cell><Data ss:Type="String">' . $model . '</Data></Cell>' . "\n";
            echo '<Cell><Data ss:Type="String">' . $code . '</Data></Cell>' . "\n";
            echo '<Cell><Data ss:Type="String">' . $status . '</Data></Cell>' . "\n";
            echo '<Cell><Data ss:Type="String">' . $serial . '</Data></Cell>' . "\n";
            $mac = htmlspecialchars($radio['mac_address'] ?? '', ENT_XML1);
            echo '<Cell><Data ss:Type="String">' . $mac . '</Data></Cell>' . "\n";
            echo '<Cell><Data ss:Type="String">' . $activity . '</Data></Cell>' . "\n";
            echo '</Row>' . "\n";
        }
        
        echo '</Table>' . "\n";
        echo '</Worksheet>' . "\n";
        echo '</Workbook>' . "\n";
        
        exit;
    }

    public function importAction() {
        $model = new Radio();
        $activityModel = new Activity();
        $error = null;
        $success = null;
        $imported = 0;
        $skipped = 0;
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['excel_file']['tmp_name'];
                $fileName = $_FILES['excel_file']['name'];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                try {
                    $data = $this->parseExcelFile($file, $fileExt);
                    
                    if (empty($data)) {
                        $error = "Le fichier est vide ou le format n'est pas reconnu";
                    } else {
                        // Récupérer toutes les activités pour le mapping
                        $activities = $activityModel->getAll();
                        $activityMap = [];
                        foreach ($activities as $activity) {
                            $activityMap[strtolower(trim($activity['name']))] = $activity['id'];
                        }

                        // Mapping des statuts
                        $statusMap = [
                            'actif' => 'disponible',
                            'active' => 'disponible',
                            'disponible' => 'disponible',
                            'empruntée' => 'empruntee',
                            'empruntee' => 'empruntee',
                            'en réparation' => 'reparation',
                            'reparation' => 'reparation',
                            'rebut' => 'rebut'
                        ];

                        foreach ($data as $rowIndex => $row) {
                            try {
                                // Mapper les colonnes selon le format Excel
                                $code = trim($row['Nom'] ?? $row['nom'] ?? $row['Code'] ?? $row['code'] ?? '');
                                $modelName = trim($row['Modèle'] ?? $row['modele'] ?? $row['Model'] ?? $row['model'] ?? '');
                                $status = trim($row['Statut'] ?? $row['statut'] ?? $row['Status'] ?? $row['status'] ?? 'disponible');
                                $serial = trim($row['SN'] ?? $row['sn'] ?? $row['Numéro de série'] ?? $row['serial_number'] ?? '');
                                $mac = trim($row['Adresse MAC'] ?? $row['adresse mac'] ?? $row['MAC'] ?? $row['mac_address'] ?? '');
                                $activityName = trim($row['Emplacement / Activité'] ?? $row['Activité'] ?? $row['activity'] ?? $row['Emplacement'] ?? '');

                                if (empty($code)) {
                                    // Ignorer silencieusement les lignes sans code
                                    continue;
                                }

                                $radioModel = new Radio();
                                
                                // Convertir le statut
                                $status = $statusMap[strtolower($status)] ?? 'disponible';

                                // Trouver l'activité
                                $activityId = null;
                                if (!empty($activityName)) {
                                    $activityId = $activityMap[strtolower(trim($activityName))] ?? null;
                                }

                                // Préparer les données
                                $radioData = [
                                    'code' => $code,
                                    'model' => $modelName,
                                    'status' => $status,
                                    'serial_number' => $serial,
                                    'mac_address' => $mac,
                                    'activity_id' => $activityId,
                                    'comments' => ''
                                ];

                                // Vérifier si la radio existe déjà
                                $existingRadio = $radioModel->getByCode($code);
                                if ($existingRadio) {
                                    // Mettre à jour la radio existante
                                    $radioModel->update($existingRadio['id'], $radioData);
                                    $imported++;
                                } else {
                                    // Créer une nouvelle radio
                                    $radioModel->create($radioData);
                                    $imported++;
                                }
                            } catch (Exception $e) {
                                // Ignorer silencieusement les erreurs
                                continue;
                            }
                        }

                        if ($imported > 0) {
                            $success = "$imported radio(s) importée(s) ou mise(s) à jour avec succès";
                        } else {
                            $error = "Aucune radio n'a pu être importée. Vérifiez le format du fichier.";
                        }
                    }
                } catch (Exception $e) {
                    $error = "Erreur lors de l'import: " . $e->getMessage();
                }
            } else {
                $error = "Veuillez sélectionner un fichier valide";
            }
        }

        $this->render('radio/import', [
            'error' => $error,
            'success' => $success,
            'imported' => $imported,
            'skipped' => $skipped,
            'errors' => $errors,
            'activities' => $activityModel->getAll()
        ]);
    }

    private function parseExcelFile($file, $ext) {
        $data = [];

        if ($ext === 'csv') {
            // Parser CSV
            if (($handle = fopen($file, "r")) !== FALSE) {
                $headers = fgetcsv($handle, 1000, ",");
                if ($headers) {
                    // Nettoyer les en-têtes
                    $headers = array_map('trim', $headers);
                    
                    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        if (count($row) === count($headers)) {
                            $data[] = array_combine($headers, $row);
                        }
                    }
                }
                fclose($handle);
            }
        } elseif (in_array($ext, ['xls', 'xlsx'])) {
            // Parser Excel XML (format SpreadsheetML)
            $xml = file_get_contents($file);
            if ($xml) {
                // Essayer de parser comme XML Excel
                libxml_use_internal_errors(true);
                $dom = new DOMDocument();
                if (@$dom->loadXML($xml)) {
                    $rows = $dom->getElementsByTagName('Row');
                    $headers = [];
                    $firstRow = true;
                    
                    foreach ($rows as $rowIndex => $row) {
                        $cells = $row->getElementsByTagName('Cell');
                        $rowData = [];
                        $cellIndex = 0;
                        
                        foreach ($cells as $cell) {
                            $dataNode = $cell->getElementsByTagName('Data')->item(0);
                            if ($dataNode) {
                                $value = trim($dataNode->nodeValue);
                                
                                if ($firstRow) {
                                    $headers[] = $value;
                                } else {
                                    $header = $headers[$cellIndex] ?? "Colonne" . ($cellIndex + 1);
                                    $rowData[$header] = $value;
                                }
                                $cellIndex++;
                            }
                        }
                        
                        if ($firstRow) {
                            $firstRow = false;
                        } elseif (!empty($rowData)) {
                            $data[] = $rowData;
                        }
                    }
                } else {
                    // Si ce n'est pas du XML, essayer comme CSV
                    return $this->parseExcelFile($file, 'csv');
                }
            }
        }

        return $data;
    }
}

