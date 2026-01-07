<?php
/**
 * Gestion du journal d'audit (traçabilité inviolable)
 */

class AuditLog {
    public static function logAction($userId, $actionType, $entityType, $entityId, $description) {
        $db = Database::getInstance();
        $ip = getClientIp();
        
        $db->query(
            "INSERT INTO audit_log (user_id, action_type, entity_type, entity_id, description, ip_address) 
             VALUES (?, ?, ?, ?, ?, ?)",
            [$userId, $actionType, $entityType, $entityId, $description, $ip]
        );
    }

    public static function logLogin($userId, $success) {
        $db = Database::getInstance();
        $ip = getClientIp();
        
        $db->query(
            "INSERT INTO login_history (user_id, ip_address, success) VALUES (?, ?, ?)",
            [$userId, $ip, $success ? 1 : 0]
        );
    }

    public static function getLogs($limit = 100, $offset = 0, $filters = []) {
        $db = Database::getInstance();
        $where = [];
        $params = [];

        if (!empty($filters['action_type'])) {
            $where[] = "action_type = ?";
            $params[] = $filters['action_type'];
        }

        if (!empty($filters['entity_type'])) {
            $where[] = "entity_type = ?";
            $params[] = $filters['entity_type'];
        }

        if (!empty($filters['date_from'])) {
            $where[] = "created_at >= ?";
            $params[] = $filters['date_from'];
        }

        if (!empty($filters['date_to'])) {
            $where[] = "created_at <= ?";
            $params[] = $filters['date_to'];
        }

        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
        $params[] = $limit;
        $params[] = $offset;

        return $db->fetchAll(
            "SELECT al.*, u.username 
             FROM audit_log al 
             LEFT JOIN users u ON al.user_id = u.id 
             $whereClause
             ORDER BY al.created_at DESC 
             LIMIT ? OFFSET ?",
            $params
        );
    }

    public static function getLoginHistory($limit = 100) {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT lh.*, u.username 
             FROM login_history lh 
             LEFT JOIN users u ON lh.user_id = u.id 
             ORDER BY lh.login_at DESC 
             LIMIT ?",
            [$limit]
        );
    }
}

