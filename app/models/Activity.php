<?php
/**
 * Modèle pour la gestion des activités
 */

class Activity {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        return $this->db->fetchAll(
            "SELECT a.*, 
                COUNT(r.id) as total_radios,
                SUM(CASE WHEN r.status = 'disponible' THEN 1 ELSE 0 END) as radios_disponibles,
                SUM(CASE WHEN r.status = 'empruntee' THEN 1 ELSE 0 END) as radios_empruntees,
                SUM(CASE WHEN r.status = 'reparation' THEN 1 ELSE 0 END) as radios_reparation
             FROM activities a 
             LEFT JOIN radios r ON a.id = r.activity_id 
             GROUP BY a.id 
             ORDER BY a.name ASC"
        );
    }

    public function getById($id) {
        return $this->db->fetchOne(
            "SELECT * FROM activities WHERE id = ?",
            [$id]
        );
    }

    public function create($data) {
        $this->db->query(
            "INSERT INTO activities (name, description) VALUES (?, ?)",
            [$data['name'], $data['description'] ?? null]
        );

        $id = $this->db->lastInsertId();
        
        AuditLog::logAction(
            Auth::getUserId(),
            'CREATE_ACTIVITY',
            'activity',
            $id,
            "Création de l'activité: {$data['name']}"
        );

        return $id;
    }

    public function update($id, $data) {
        $oldData = $this->getById($id);
        
        $this->db->query(
            "UPDATE activities SET name = ?, description = ? WHERE id = ?",
            [$data['name'], $data['description'] ?? null, $id]
        );

        AuditLog::logAction(
            Auth::getUserId(),
            'UPDATE_ACTIVITY',
            'activity',
            $id,
            "Modification de l'activité: {$oldData['name']} → {$data['name']}"
        );
    }

    public function delete($id) {
        $activity = $this->getById($id);
        
        // Vérifier qu'aucune radio n'est associée
        $count = $this->db->fetchOne("SELECT COUNT(*) as count FROM radios WHERE activity_id = ?", [$id]);
        if ($count && $count['count'] > 0) {
            throw new Exception("Impossible de supprimer l'activité : des radios y sont associées");
        }

        $this->db->query("DELETE FROM activities WHERE id = ?", [$id]);

        AuditLog::logAction(
            Auth::getUserId(),
            'DELETE_ACTIVITY',
            'activity',
            $id,
            "Suppression de l'activité: {$activity['name']}"
        );
    }

    public function getAvailableRadios($activityId) {
        return $this->db->fetchAll(
            "SELECT r.*, a.name as activity_name 
             FROM radios r 
             LEFT JOIN activities a ON r.activity_id = a.id 
             WHERE r.activity_id = ? AND r.status = 'disponible' 
             ORDER BY r.code ASC",
            [$activityId]
        );
    }
}

