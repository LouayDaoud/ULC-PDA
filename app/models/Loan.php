<?php
/**
 * Modèle pour la gestion des emprunts
 */

class Loan {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll($filters = []) {
        $where = [];
        $params = [];

        if (!empty($filters['status'])) {
            $where[] = "l.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['activity_id'])) {
            $where[] = "l.activity_id = ?";
            $params[] = $filters['activity_id'];
        }

        if (!empty($filters['search'])) {
            $where[] = "(l.borrower_name LIKE ? OR l.borrower_id LIKE ? OR r.code LIKE ?)";
            $search = "%{$filters['search']}%";
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }

        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

        return $this->db->fetchAll(
            "SELECT l.*, r.code as radio_code, r.serial_number, a.name as activity_name 
             FROM loans l 
             JOIN radios r ON l.radio_id = r.id 
             JOIN activities a ON l.activity_id = a.id 
             $whereClause
             ORDER BY l.borrowed_at DESC",
            $params
        );
    }

    public function getById($id) {
        return $this->db->fetchOne(
            "SELECT l.*, r.code as radio_code, r.serial_number, a.name as activity_name 
             FROM loans l 
             JOIN radios r ON l.radio_id = r.id 
             JOIN activities a ON l.activity_id = a.id 
             WHERE l.id = ?",
            [$id]
        );
    }

    public function getActiveByRadioId($radioId) {
        return $this->db->fetchOne(
            "SELECT * FROM loans 
             WHERE radio_id = ? AND status IN ('en_cours', 'en_retard') 
             ORDER BY borrowed_at DESC 
             LIMIT 1",
            [$radioId]
        );
    }

    public function getOverdue() {
        return $this->db->fetchAll(
            "SELECT l.*, r.code as radio_code, a.name as activity_name 
             FROM loans l 
             JOIN radios r ON l.radio_id = r.id 
             JOIN activities a ON l.activity_id = a.id 
             WHERE l.status = 'en_cours' AND l.due_at < NOW() 
             ORDER BY l.due_at ASC"
        );
    }

    public function create($data) {
        // Vérifier que la radio est disponible
        $radioModel = new Radio();
        $radio = $radioModel->getById($data['radio_id']);
        
        if (!$radio || $radio['status'] !== 'disponible') {
            throw new Exception("La radio n'est pas disponible");
        }

        $this->db->query(
            "INSERT INTO loans (radio_id, borrower_name, borrower_id, activity_id, borrowed_at, due_at, state_out, comments) 
             VALUES (?, ?, ?, ?, NOW(), ?, ?, ?)",
            [
                $data['radio_id'],
                $data['borrower_name'],
                $data['borrower_id'] ?? null,
                $data['activity_id'],
                $data['due_at'] ?? null,
                $data['state_out'] ?? null,
                $data['comments'] ?? null
            ]
        );

        $id = $this->db->lastInsertId();

        // Mettre à jour le statut de la radio
        $radioModel->updateStatus($data['radio_id'], 'empruntee');

        AuditLog::logAction(
            Auth::getUserId(),
            'CREATE_LOAN',
            'loan',
            $id,
            "Emprunt créé: Radio {$radio['code']} empruntée par {$data['borrower_name']}"
        );

        return $id;
    }

    public function returnLoan($id, $data) {
        $loan = $this->getById($id);
        
        if ($loan['status'] !== 'en_cours' && $loan['status'] !== 'en_retard') {
            throw new Exception("Cet emprunt ne peut pas être retourné");
        }

        $this->db->query(
            "UPDATE loans SET 
                returned_at = NOW(), 
                status = 'retourne',
                state_in = ?,
                comments = CONCAT(IFNULL(comments, ''), IFNULL(?, ''))
             WHERE id = ?",
            [
                $data['state_in'] ?? null,
                $data['comments'] ? "\nRetour: " . $data['comments'] : null,
                $id
            ]
        );

        // Remettre la radio en disponible
        $radioModel = new Radio();
        $radioModel->updateStatus($loan['radio_id'], 'disponible');

        AuditLog::logAction(
            Auth::getUserId(),
            'RETURN_LOAN',
            'loan',
            $id,
            "Retour de la radio {$loan['radio_code']} empruntée par {$loan['borrower_name']}"
        );
    }

    public function markAsLost($id) {
        $loan = $this->getById($id);
        
        $this->db->query(
            "UPDATE loans SET status = 'perdu' WHERE id = ?",
            [$id]
        );

        // Marquer la radio comme perdue (rebut)
        $radioModel = new Radio();
        $radioModel->updateStatus($loan['radio_id'], 'rebut');

        AuditLog::logAction(
            Auth::getUserId(),
            'MARK_LOAN_LOST',
            'loan',
            $id,
            "Emprunt marqué comme perdu: Radio {$loan['radio_code']}"
        );
    }

    public function updateOverdueStatus() {
        // Mettre à jour les emprunts en retard
        $this->db->query(
            "UPDATE loans 
             SET status = 'en_retard' 
             WHERE status = 'en_cours' AND due_at < NOW()"
        );
    }

    public function getStats() {
        return [
            'total' => $this->db->fetchOne("SELECT COUNT(*) as count FROM loans")['count'],
            'en_cours' => $this->db->fetchOne("SELECT COUNT(*) as count FROM loans WHERE status = 'en_cours'")['count'],
            'retourne' => $this->db->fetchOne("SELECT COUNT(*) as count FROM loans WHERE status = 'retourne'")['count'],
            'en_retard' => $this->db->fetchOne("SELECT COUNT(*) as count FROM loans WHERE status = 'en_retard'")['count'],
            'perdu' => $this->db->fetchOne("SELECT COUNT(*) as count FROM loans WHERE status = 'perdu'")['count'],
        ];
    }
}

