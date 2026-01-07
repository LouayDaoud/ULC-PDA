<?php
/**
 * Modèle pour la gestion des radios
 */

class Radio {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll($filters = []) {
        $where = [];
        $params = [];

        if (!empty($filters['status'])) {
            $where[] = "r.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['activity_id'])) {
            $where[] = "r.activity_id = ?";
            $params[] = $filters['activity_id'];
        }

        if (!empty($filters['search'])) {
            $where[] = "(r.code LIKE ? OR r.serial_number LIKE ? OR r.model LIKE ?)";
            $search = "%{$filters['search']}%";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

        return $this->db->fetchAll(
            "SELECT r.*, a.name as activity_name 
             FROM radios r 
             LEFT JOIN activities a ON r.activity_id = a.id 
             $whereClause
             ORDER BY r.code ASC",
            $params
        );
    }

    public function getById($id) {
        return $this->db->fetchOne(
            "SELECT r.*, a.name as activity_name 
             FROM radios r 
             LEFT JOIN activities a ON r.activity_id = a.id 
             WHERE r.id = ?",
            [$id]
        );
    }

    public function getByCode($code) {
        return $this->db->fetchOne(
            "SELECT * FROM radios WHERE code = ?",
            [$code]
        );
    }

    public function create($data) {
        $this->db->query(
            "INSERT INTO radios (code, serial_number, model, status, activity_id, comments) 
             VALUES (?, ?, ?, ?, ?, ?)",
            [
                $data['code'],
                $data['serial_number'] ?? null,
                $data['model'] ?? null,
                $data['status'] ?? 'disponible',
                $data['activity_id'] ?? null,
                $data['comments'] ?? null
            ]
        );

        $id = $this->db->lastInsertId();
        
        AuditLog::logAction(
            Auth::getUserId(),
            'CREATE_RADIO',
            'radio',
            $id,
            "Création de la radio: {$data['code']}"
        );

        return $id;
    }

    public function update($id, $data) {
        $oldData = $this->getById($id);
        
        $this->db->query(
            "UPDATE radios SET 
                code = ?, serial_number = ?, model = ?, status = ?, 
                activity_id = ?, comments = ? 
             WHERE id = ?",
            [
                $data['code'],
                $data['serial_number'] ?? null,
                $data['model'] ?? null,
                $data['status'] ?? 'disponible',
                $data['activity_id'] ?? null,
                $data['comments'] ?? null,
                $id
            ]
        );

        $changes = [];
        foreach ($data as $key => $value) {
            if (isset($oldData[$key]) && $oldData[$key] != $value) {
                $changes[] = "$key: {$oldData[$key]} → $value";
            }
        }

        AuditLog::logAction(
            Auth::getUserId(),
            'UPDATE_RADIO',
            'radio',
            $id,
            "Modification de la radio {$data['code']}: " . implode(", ", $changes)
        );
    }

    public function updateStatus($id, $status) {
        $radio = $this->getById($id);
        $this->db->query(
            "UPDATE radios SET status = ? WHERE id = ?",
            [$status, $id]
        );

        AuditLog::logAction(
            Auth::getUserId(),
            'UPDATE_RADIO_STATUS',
            'radio',
            $id,
            "Changement d'état de la radio {$radio['code']}: {$radio['status']} → $status"
        );
    }

    public function delete($id) {
        $radio = $this->getById($id);
        
        // Suppression logique (marquer comme rebut)
        $this->db->query(
            "UPDATE radios SET status = 'rebut' WHERE id = ?",
            [$id]
        );

        AuditLog::logAction(
            Auth::getUserId(),
            'DELETE_RADIO',
            'radio',
            $id,
            "Suppression (mise au rebut) de la radio: {$radio['code']}"
        );
    }

    public function getStats() {
        return [
            'total' => $this->db->fetchOne("SELECT COUNT(*) as count FROM radios")['count'],
            'disponible' => $this->db->fetchOne("SELECT COUNT(*) as count FROM radios WHERE status = 'disponible'")['count'],
            'empruntee' => $this->db->fetchOne("SELECT COUNT(*) as count FROM radios WHERE status = 'empruntee'")['count'],
            'reparation' => $this->db->fetchOne("SELECT COUNT(*) as count FROM radios WHERE status = 'reparation'")['count'],
            'rebut' => $this->db->fetchOne("SELECT COUNT(*) as count FROM radios WHERE status = 'rebut'")['count'],
        ];
    }
}

