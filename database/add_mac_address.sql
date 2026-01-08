-- Script pour ajouter la colonne Adresse MAC à la table radios
-- Exécutez ce script dans votre base de données pda_ulc

USE pda_ulc;

ALTER TABLE radios 
ADD COLUMN mac_address VARCHAR(17) NULL AFTER serial_number;

-- Commentaire sur la colonne
ALTER TABLE radios 
MODIFY COLUMN mac_address VARCHAR(17) NULL COMMENT 'Adresse MAC au format XX:XX:XX:XX:XX:XX';

