<?php
/**
 * Script d'installation - Génération du hash du mot de passe admin
 * 
 * Usage: php install.php
 * 
 * Ce script génère le hash du mot de passe pour l'utilisateur admin.
 * Copiez le hash généré dans le fichier database/schema.sql
 */

echo "=== Script d'installation ULC PDA ===\n\n";

$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Mot de passe: $password\n";
echo "Hash généré: $hash\n\n";
echo "Copiez cette ligne dans database/schema.sql :\n";
echo "('admin', '$hash');\n\n";

echo "Pour tester le hash, utilisez:\n";
echo "php -r \"var_dump(password_verify('$password', '$hash'));\"\n";

