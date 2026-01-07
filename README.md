# ULC PDA - Application de gestion des radios

Application web PHP native pour la gestion du cycle de vie des radios, optimisée pour une utilisation sur PDA / navigateur mobile.

## Fonctionnalités

- ✅ **Gestion des inventaires** : Création, modification, suppression, recherche et suivi des radios
- ✅ **Gestion des emprunts** : Enregistrement des emprunts et retours avec traçabilité complète
- ✅ **Gestion des activités** : Organisation des radios par activité avec statistiques et vue détaillée des radios disponibles
- ✅ **Maintenance & réparations** : Suivi du cycle de réparation des radios
- ✅ **Rapports & statistiques** : Tableaux de bord et exports CSV
- ✅ **Journal d'audit inviolable** : Traçabilité complète de toutes les actions
- ✅ **Interface adaptée PDA** : Design responsive avec gros boutons et navigation tactile
- ✅ **Personnalisation** : Image de fond personnalisable pour la page de connexion

## Prérequis

- PHP ≥ 8.0
- MySQL ou MariaDB
- Apache ou Nginx
- Extension PDO pour PHP

## Installation

### 1. Cloner ou télécharger le projet

```bash
cd /var/www/html  # ou votre répertoire web
# Copier les fichiers du projet
```

### 2. Configuration de la base de données

1. Créer la base de données (remplacez `pda_ulc` par le nom de votre choix) :
```sql
CREATE DATABASE pda_ulc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Importer le schéma :
```bash
mysql -u root -p pda_ulc < database/schema.sql
```

**Note** : Si vous utilisez un autre nom de base de données, n'oubliez pas de le configurer dans `config/database.php`.

### 3. Configuration de l'application

1. Copier le fichier de configuration exemple :
```bash
cp config/database.php.example config/database.php
```

2. Modifier le fichier `config/database.php` avec vos paramètres de connexion :

```php
return [
    'host' => 'localhost',
    'dbname' => 'pda_ulc',  // Remplacez par le nom de votre base de données
    'username' => 'votre_utilisateur',
    'password' => 'votre_mot_de_passe',
    'charset' => 'utf8mb4'
];
```

3. **Configuration de BASE_URL** (si l'application est dans un sous-dossier) :

Si votre application est accessible via `http://localhost/ULC-PDA/`, modifiez `config/config.php` :

```php
define('BASE_URL', '/ULC-PDA/');  // Ajustez selon votre configuration
```

Si l'application est à la racine du serveur web, utilisez :
```php
define('BASE_URL', '/');
```

### 4. Configuration du serveur web

#### Apache

Assurez-vous que le module `mod_rewrite` est activé et que le `.htaccess` est pris en compte.

#### Nginx

Ajouter cette configuration dans votre bloc `server` :

```nginx
location / {
    try_files $uri $uri/ /public/index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
    fastcgi_index index.php;
    include fastcgi_params;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
}
```

### 5. Permissions

Assurez-vous que le serveur web a les permissions d'écriture si nécessaire :

```bash
chmod -R 755 /chemin/vers/ulc-pda
chown -R www-data:www-data /chemin/vers/ulc-pda
```

## Connexion par défaut

- **Utilisateur** : `admin`
- **Mot de passe** : `admin123`

⚠️ **IMPORTANT** : 
1. Avant la première utilisation, générez un nouveau hash pour le mot de passe admin :
   ```bash
   php install.php
   ```
   Copiez le hash généré dans `database/schema.sql` à la ligne 98.

2. Changez immédiatement le mot de passe après la première connexion dans les paramètres !

## Structure du projet

```
ULC-PDA/
├── app/
│   ├── controllers/     # Contrôleurs MVC
│   ├── core/           # Classes de base (Database, Auth, Router, etc.)
│   ├── models/         # Modèles de données
│   └── views/          # Vues (templates)
├── assets/
│   ├── css/            # Feuilles de style
│   ├── js/             # JavaScript
│   └── imp.jpeg         # Image de fond pour la page de connexion (personnalisable)
├── config/             # Configuration
├── database/           # Scripts SQL
└── public/             # Point d'entrée public
    └── index.php
```

## Utilisation

### Navigation

L'application dispose d'un menu de navigation principal avec les sections suivantes :

- **Tableau de bord** : Vue d'ensemble avec statistiques et alertes
- **Radios** : Gestion de l'inventaire des radios
- **Emprunts** : Gestion des emprunts et retours
- **Activités** : Gestion des activités et affectations, vue détaillée des radios disponibles par activité
- **Maintenance** : Suivi des réparations
- **Rapports** : Statistiques et exports
- **Paramètres** : Configuration et journal d'audit

### Fonctionnalités principales

#### Gestion des radios

- Créer une nouvelle radio avec code unique
- Modifier les informations (numéro de série, modèle, état, activité)
- Supprimer une radio (avec confirmation)
- Rechercher et filtrer les radios
- Marquer une radio comme disponible, empruntée, en réparation ou rebut

#### Gestion des emprunts

- Enregistrer un nouvel emprunt (radio, emprunteur, activité, date de retour)
- Enregistrer le retour d'une radio avec état au retour
- Suivre les emprunts en retard
- Marquer un emprunt comme perdu

#### Maintenance

- Déclarer une panne sur une radio
- Suivre le cycle de réparation (en attente → diagnostic → réparation → test → réparée)
- Historique des maintenances par radio

#### Traçabilité

Toutes les actions sont enregistrées dans le journal d'audit :
- Création/modification/suppression de radios
- Emprunts et retours
- Maintenances
- Connexions (succès/échec)
- Changements de mot de passe

Le journal d'audit est **inviolable** : aucune suppression n'est possible via l'interface.

## Sécurité

- Mots de passe hashés avec `password_hash()` (bcrypt)
- Protection CSRF sur les formulaires sensibles
- Validation côté serveur de toutes les entrées
- Vérification de session sur toutes les pages
- Journal d'audit non modifiable

## Exports

L'application permet d'exporter les données en CSV :
- Inventaire des radios
- Emprunts
- Activités
- Maintenances
- Journal d'audit

## Personnalisation

### Image de fond de la page de connexion

Pour personnaliser l'image de fond de la page de connexion :
1. Placez votre image dans le dossier `assets/` (par exemple `assets/imp.jpeg`)
2. Le CSS chargera automatiquement l'image depuis `assets/imp.jpeg`
3. Vous pouvez modifier le chemin dans `assets/css/style.css` ou `app/views/login/index.php`

## Support

Pour toute question ou problème, consultez la documentation ou contactez l'administrateur système.

## Licence

Application propriétaire - Usage interne uniquement.

