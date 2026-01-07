-- Base de données pour l'application ULC PDA - Gestion des Radios
-- Créer la base de données : CREATE DATABASE pda_ulc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- Remplacez 'pda_ulc' par le nom de votre base de données

USE pda_ulc;

-- Table des utilisateurs (admin uniquement)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    last_login_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des activités
CREATE TABLE IF NOT EXISTS activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des radios
CREATE TABLE IF NOT EXISTS radios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    serial_number VARCHAR(100) NULL,
    model VARCHAR(100) NULL,
    status ENUM('disponible', 'empruntee', 'reparation', 'rebut') NOT NULL DEFAULT 'disponible',
    activity_id INT NULL,
    comments TEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (activity_id) REFERENCES activities(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des emprunts
CREATE TABLE IF NOT EXISTS loans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    radio_id INT NOT NULL,
    borrower_name VARCHAR(100) NOT NULL,
    borrower_id VARCHAR(50) NULL,
    activity_id INT NOT NULL,
    borrowed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    due_at DATETIME NULL,
    returned_at DATETIME NULL,
    status ENUM('en_cours', 'retourne', 'perdu', 'en_retard') NOT NULL DEFAULT 'en_cours',
    state_out VARCHAR(50) NULL,
    state_in VARCHAR(50) NULL,
    comments TEXT NULL,
    FOREIGN KEY (radio_id) REFERENCES radios(id) ON DELETE RESTRICT,
    FOREIGN KEY (activity_id) REFERENCES activities(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des maintenances
CREATE TABLE IF NOT EXISTS maintenances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    radio_id INT NOT NULL,
    reported_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    reported_by VARCHAR(100) NOT NULL,
    issue_type VARCHAR(100) NOT NULL,
    description TEXT NULL,
    status ENUM('en_attente', 'diagnostic', 'reparation', 'test', 'reparee', 'rebut') NOT NULL DEFAULT 'en_attente',
    closed_at DATETIME NULL,
    comments TEXT NULL,
    FOREIGN KEY (radio_id) REFERENCES radios(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table d'audit (traçabilité inviolable)
CREATE TABLE IF NOT EXISTS audit_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action_type VARCHAR(50) NOT NULL,
    entity_type VARCHAR(50) NOT NULL,
    entity_id INT NULL,
    description TEXT NOT NULL,
    ip_address VARCHAR(45) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_created_at (created_at),
    INDEX idx_entity (entity_type, entity_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table d'historique des connexions
CREATE TABLE IF NOT EXISTS login_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    login_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45) NULL,
    success TINYINT(1) NOT NULL DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_login_at (login_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertion d'un utilisateur admin par défaut (mot de passe: admin123)
-- À changer immédiatement après la première connexion !
-- 
-- Pour générer un nouveau hash, exécutez: php install.php
-- Puis copiez le hash généré ci-dessous
INSERT INTO users (username, password_hash) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

