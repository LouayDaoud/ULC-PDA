<?php
/**
 * Modèle pour la gestion des maintenances
 */

class Maintenance {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll($filters = []) {
        $where = [];
        $params = [];

        if (!empty($filters['status'])) {
            $where[] = "m.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['radio_id'])) {
            $where[] = "m.radio_id = ?";
            $params[] = $filters['radio_id'];
        }

        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

        return $this->db->fetchAll(
            "SELECT m.*, r.code as radio_code, r.serial_number 
             FROM maintenances m 
             JOIN radios r ON m.radio_id = r.id 
             $whereClause
             ORDER BY m.reported_at DESC",
            $params
        );
    }

    public function getById($id) {
        return $this->db->fetchOne(
            "SELECT m.*, r.code as radio_code, r.serial_number 
             FROM maintenances m 
             JOIN radios r ON m.radio_id = r.id 
             WHERE m.id = ?",
            [$id]
        );
    }

    public function getByRadioId($radioId) {
        return $this->db->fetchAll(
            "SELECT * FROM maintenances 
             WHERE radio_id = ? 
             ORDER BY reported_at DESC",
            [$radioId]
        );
    }

    public function getActive() {
        return $this->db->fetchAll(
            "SELECT m.*, r.code as radio_code 
             FROM maintenances m 
             JOIN radios r ON m.radio_id = r.id 
             WHERE m.status NOT IN ('reparee', 'rebut') 
             ORDER BY m.reported_at DESC"
        );
    }

    public function create($data) {
        $this->db->query(
            "INSERT INTO maintenances (radio_id, reported_by, issue_type, description, status, comments) 
             VALUES (?, ?, ?, ?, ?, ?)",
            [
                $data['radio_id'],
                $data['reported_by'],
                $data['issue_type'],
                $data['description'] ?? null,
                $data['status'] ?? 'en_attente',
                $data['comments'] ?? null
            ]
        );

        $id = $this->db->lastInsertId();

        // Mettre à jour le statut de la radio
        $radioModel = new Radio();
        $radioModel->updateStatus($data['radio_id'], 'reparation');

        $radio = $radioModel->getById($data['radio_id']);

        AuditLog::logAction(
            Auth::getUserId(),
            'CREATE_MAINTENANCE',
            'maintenance',
            $id,
            "Maintenance créée: Radio {$radio['code']} - {$data['issue_type']}"
        );

        return $id;
    }

    public function update($id, $data) {
        $oldData = $this->getById($id);
        
        $this->db->query(
            "UPDATE maintenances SET 
                issue_type = ?, description = ?, status = ?, comments = ? 
             WHERE id = ?",
            [
                $data['issue_type'],
                $data['description'] ?? null,
                $data['status'],
                $data['comments'] ?? null,
                $id
            ]
        );

        // Si la maintenance est terminée (réparée ou rebut), mettre à jour la radio
        if ($data['status'] === 'reparee') {
            $this->db->query(
                "UPDATE maintenances SET closed_at = NOW() WHERE id = ?",
                [$id]
            );
            $radioModel = new Radio();
            $radioModel->updateStatus($oldData['radio_id'], 'disponible');
        } elseif ($data['status'] === 'rebut') {
            $this->db->query(
                "UPDATE maintenances SET closed_at = NOW() WHERE id = ?",
                [$id]
            );
            $radioModel = new Radio();
            $radioModel->updateStatus($oldData['radio_id'], 'rebut');
        }

        AuditLog::logAction(
            Auth::getUserId(),
            'UPDATE_MAINTENANCE',
            'maintenance',
            $id,
            "Modification de la maintenance: Radio {$oldData['radio_code']} - Statut: {$oldData['status']} → {$data['status']}"
        );
    }

    public function getStats() {
        return [
            'total' => $this->db->fetchOne("SELECT COUNT(*) as count FROM maintenances")['count'],
            'en_attente' => $this->db->fetchOne("SELECT COUNT(*) as count FROM maintenances WHERE status = 'en_attente'")['count'],
            'diagnostic' => $this->db->fetchOne("SELECT COUNT(*) as count FROM maintenances WHERE status = 'diagnostic'")['count'],
            'reparation' => $this->db->fetchOne("SELECT COUNT(*) as count FROM maintenances WHERE status = 'reparation'")['count'],
            'test' => $this->db->fetchOne("SELECT COUNT(*) as count FROM maintenances WHERE status = 'test'")['count'],
            'reparee' => $this->db->fetchOne("SELECT COUNT(*) as count FROM maintenances WHERE status = 'reparee'")['count'],
        ];
    }
}

