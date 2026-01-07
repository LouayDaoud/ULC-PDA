-- Script pour insérer l'utilisateur admin dans la base pda_ulc
-- Mot de passe: admin123

USE pda_ulc;

-- Supprimer l'utilisateur admin s'il existe déjà
DELETE FROM users WHERE username = 'admin';

-- Insérer l'utilisateur admin avec le hash du mot de passe
INSERT INTO users (username, password_hash) VALUES 
('admin', '$2y$10$pHFpeSavWJoBvQzVdUzaZen7cGbQ7.dPht3CsQGoP/8Y4dAmvrKuO');

