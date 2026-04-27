# 📘 Guide de Déploiement — Annuaire des Expériences GEII

> Guide complet pour installer, configurer et maintenir l'application Annuaire GEII.
> Rédigé pour les administrateurs universitaires.

---

## Table des matières

1. [Prérequis techniques](#1--prérequis-techniques)
2. [Installation](#2--installation)
3. [Configuration de la base de données](#3--configuration-de-la-base-de-données)
4. [Configuration de l'envoi d'emails](#4--configuration-de-lenvoi-demails)
5. [Configuration du chemin de base](#5--configuration-du-chemin-de-base-base)
6. [Administration du site](#6--administration-du-site)
7. [Sécurité](#7--sécurité)
8. [Structure du projet](#8--structure-du-projet)
9. [Maintenance](#9--maintenance)
10. [FAQ / Dépannage](#10--faq--dépannage)

---

## 1 — Prérequis techniques

| Composant       | Version minimale | Notes                                      |
|-----------------|------------------|--------------------------------------------|
| **PHP**         | 8.0+             | Extensions requises : PDO, pdo_mysql, mbstring, openssl |
| **MySQL/MariaDB** | 5.7+ / 10.4+  | Charset `utf8mb4`                          |
| **Apache**      | 2.4+             | Module `mod_rewrite` activé                |
| **Composer**    | 2.x              | Gestionnaire de dépendances PHP            |

### Vérifier la configuration PHP

```bash
php -m | grep -E "pdo|mbstring|openssl"
```

### Vérifier mod_rewrite (Apache)

```bash
apache2ctl -M | grep rewrite
# ou sous Windows (XAMPP)
httpd -M | findstr rewrite
```

---

## 2 — Installation

### 2.1 — Copier les fichiers

Placez l'intégralité du dossier `Annuaire/` dans le répertoire web de votre serveur :

```
/var/www/html/Annuaire/          # Linux (Apache)
C:\xampp\htdocs\Annuaire\        # Windows (XAMPP)
```

### 2.2 — Installer les dépendances PHP

```bash
cd /chemin/vers/Annuaire
composer install --no-dev --optimize-autoloader
```

> **Note** : L'option `--no-dev` exclut les dépendances de développement (tests, etc.).

### 2.3 — Configurer l'environnement

Copiez le fichier d'exemple et renseignez vos valeurs :

```bash
cp .env.example .env
```

Puis éditez `.env` avec vos informations (voir sections suivantes).

### 2.4 — Permissions des dossiers (Linux)

```bash
chmod 755 -R Annuaire/
chmod 777 Annuaire/logs/
chmod 777 Annuaire/cache/
chmod 600 Annuaire/.env
```

---

## 3 — Configuration de la base de données

### 3.1 — Créer la base de données

```sql
CREATE DATABASE annuaire_geii CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3.2 — Créer un utilisateur dédié (recommandé)

```sql
CREATE USER 'annuaire_user'@'localhost' IDENTIFIED BY 'votre_mot_de_passe_securise';
GRANT ALL PRIVILEGES ON annuaire_geii.* TO 'annuaire_user'@'localhost';
FLUSH PRIVILEGES;
```

### 3.3 — Importer la structure et les données

Importez le dump SQL fourni (via phpMyAdmin ou en ligne de commande) :

```bash
mysql -u annuaire_user -p annuaire_geii < annuaire_full_export_geii.sql
```

> ⚠️ Si le fichier SQL n'est pas fourni, les tables nécessaires sont :

| Table              | Description                               |
|--------------------|-------------------------------------------|
| `admins`           | Comptes administrateurs                   |
| `entreprises`      | Répertoire des entreprises                |
| `experiences`      | Stages et alternances des étudiants       |
| `domaines`         | Domaines d'activité                       |
| `contact_messages` | Messages reçus via le formulaire contact  |
| `logs_audit`       | Journal des actions administrateur        |
| `stats_visits`     | Statistiques de visites (anonymisées)     |

### 3.4 — Créer le premier compte administrateur

```sql
INSERT INTO admins (username, password) 
VALUES ('admin', '$2y$10$HASH_GENERE_CI_DESSOUS');
```

Pour générer le hash du mot de passe :

```bash
php -r "echo password_hash('VotreMotDePasse', PASSWORD_DEFAULT);"
```

### 3.5 — Configurer le `.env`

```env
DB_HOST=localhost
DB_NAME=annuaire_geii
DB_USER=annuaire_user
DB_PASS=votre_mot_de_passe_securise
DB_PORT=3306
```

---

## 4 — Configuration de l'envoi d'emails

Le site envoie des emails dans trois cas :
- **Formulaire de contact** : notification à l'administrateur
- **Ajout d'expérience** : email de vérification à l'étudiant
- **Nouvelle expérience** : notification à l'administrateur

### 4.1 — Mode PHP Mail (recommandé sur hébergement mutualisé)

```env
MAIL_MODE=phpmail
FROM_EMAIL=noreply@votre-domaine.fr
ADMIN_EMAIL=admin@votre-domaine.fr
```

Ce mode utilise la fonction `mail()` native de PHP. Il fonctionne directement sur la plupart des hébergements sans configuration supplémentaire.

### 4.2 — Mode SMTP (serveur mail dédié)

```env
MAIL_MODE=smtp
SMTP_HOST=smtp.votre-serveur.fr
SMTP_PORT=587
SMTP_USER=votre-email@domaine.fr
SMTP_PASS=votre-mot-de-passe
FROM_EMAIL=noreply@domaine.fr
ADMIN_EMAIL=admin@domaine.fr
```

| Variable       | Description                                             |
|----------------|---------------------------------------------------------|
| `MAIL_MODE`    | `phpmail` ou `smtp`                                     |
| `SMTP_HOST`    | Serveur SMTP (ex: `smtp.gmail.com`, `smtp.office365.com`) |
| `SMTP_PORT`    | Port SMTP : `587` (STARTTLS) ou `465` (SSL)            |
| `SMTP_USER`    | Adresse email d'authentification                        |
| `SMTP_PASS`    | Mot de passe du compte email                            |
| `FROM_EMAIL`   | Adresse affichée comme expéditeur                       |
| `ADMIN_EMAIL`  | Adresse qui reçoit les messages de contact              |

### 4.3 — Tester l'envoi d'emails

Allez sur la page **Contact** du site et envoyez un message test. Vérifiez que l'email arrive bien à l'adresse `ADMIN_EMAIL`.

---

## 5 — Configuration du chemin de base ($BASE)

La variable `$BASE` définit le préfixe URL du projet. Par défaut, elle est configurée sur `/Annuaire`.

### Si votre site est accessible via `https://domaine.fr/Annuaire/`

Aucune modification nécessaire — la valeur par défaut convient.

### Si votre site est sous un chemin différent

Par exemple, si le projet est dans `/stage-geii/`, modifiez la variable `$BASE` dans les fichiers suivants :

| Fichier                    | Ligne à modifier                    |
|----------------------------|-------------------------------------|
| `partials/header.php`      | `$BASE = '/Annuaire';` → `$BASE = '/stage-geii';` |
| `partials/footer.php`      | `$BASE = '/Annuaire';` → `$BASE = '/stage-geii';` |
| `pages/contact.php`        | `$BASE = '/Annuaire';` → `$BASE = '/stage-geii';` |

### Si le projet est à la racine du domaine

```php
$BASE = '';
```

> **Attention** : le fichier `.htaccess` contient aussi des règles liées au chemin. Adaptez la ligne `ErrorDocument 404` en conséquence.

---

## 6 — Administration du site

### 6.1 — Accès au panneau d'administration

URL : `https://votre-domaine.fr/Annuaire/admin/`

Connectez-vous avec le compte créé à l'étape 3.4.

### 6.2 — Fonctionnalités disponibles

| Fonctionnalité                | Description                                                |
|-------------------------------|------------------------------------------------------------|
| **Tableau de bord**           | Vue d'ensemble des expériences, stats de visites           |
| **Gestion des expériences**   | Approuver, modifier, supprimer des expériences             |
| **Corbeille**                 | Restaurer ou supprimer définitivement (délai de 1h)        |
| **Logs d'audit**              | Historique de toutes les actions administrateur             |

### 6.3 — Workflow des expériences étudiantes

```
Étudiant soumet une expérience
        ↓
Email de vérification envoyé
        ↓
Étudiant clique sur le lien de vérification
        ↓
L'expérience passe en statut "en attente d'approbation"
        ↓
L'admin approuve depuis le panneau d'administration
        ↓
L'expérience est visible publiquement
```

---

## 7 — Sécurité

### 7.1 — Protections intégrées

| Protection                     | Détail                                               |
|--------------------------------|------------------------------------------------------|
| **CSRF**                       | Tokens uniques sur tous les formulaires              |
| **Sessions sécurisées**        | `httponly`, `samesite=Strict`, expiration 15 min     |
| **Préparation SQL**            | Requêtes paramétrées (PDO) contre les injections SQL |
| **Échappement HTML**           | `htmlspecialchars()` sur toutes les sorties          |
| **Honeypot anti-spam**         | Champ caché sur le formulaire de contact             |
| **En-têtes de sécurité**       | X-Frame-Options, X-XSS-Protection, X-Content-Type   |
| **Logs d'audit**               | Traçabilité des connexions et actions admin          |
| **Soft delete**                | Suppression réversible (corbeille) avec délai de 1h  |

### 7.2 — Fichiers protégés par `.htaccess`

Les fichiers suivants sont **inaccessibles** depuis le navigateur :
- `.env` (configuration)
- `composer.json`, `composer.lock`
- Fichiers `.sql`, `.md`, `.log`, `.ini`
- Dossiers : `partials/`, `vendor/`, `logs/`, `cache/`

### 7.3 — Recommandations supplémentaires

- **Changez le mot de passe admin** après la première connexion
- **Activez HTTPS** sur votre serveur (certificat Let's Encrypt gratuit)
- **Mettez à jour PHP** et les dépendances Composer régulièrement

---

## 8 — Structure du projet

```
Annuaire/
├── .env                    # Variables d'environnement (NON versionné)
├── .env.example            # Template de configuration
├── .htaccess               # Sécurité et performance Apache
├── .gitignore              # Fichiers ignorés par Git
├── composer.json           # Dépendances PHP
├── config.php              # Parseur .env et fonction env()
├── config-mail.php         # Configuration email
├── index.php               # Page d'accueil
├── verify.php              # Vérification email des étudiants
├── robots.txt              # Directives pour les moteurs de recherche
│
├── admin/                  # Panneau d'administration
│   ├── _bootstrap.php      # Initialisation session/auth/helpers
│   ├── index.php           # Tableau de bord admin
│   ├── login.php           # Page de connexion
│   ├── logout.php          # Déconnexion
│   ├── edit.php            # Édition d'une expérience
│   ├── approve.php         # Approbation d'une expérience
│   ├── delete.php          # Soft delete
│   ├── delete_permanent.php# Suppression définitive
│   ├── restore.php         # Restauration depuis la corbeille
│   └── trash.php           # Vue corbeille
│
├── api/                    # Backend / Services
│   ├── connexion.php       # Connexion PDO à la base de données
│   ├── MailService.php     # Service d'envoi d'emails
│   ├── annuaire.php        # API JSON pour l'annuaire
│   ├── experiences.php     # API JSON pour les expériences
│   ├── search_global.php   # Recherche globale
│   └── cleanup.php         # Nettoyage auto des soft deletes expirés
│
├── assets/                 # Ressources statiques
│   ├── css/style.css       # Feuille de style principale
│   ├── js/                 # Scripts JavaScript
│   └── images/             # Logos, images, favicon
│
├── docs/                   # Documents PDF (ressources étudiantes)
│
├── pages/                  # Pages publiques
│   ├── annuaire.php        # Annuaire avec filtres et recherche
│   ├── experiences.php     # Détail d'une expérience
│   ├── entreprises.php     # Page entreprise
│   ├── ajouter.php         # Formulaire d'ajout d'expérience
│   ├── contact.php         # Formulaire de contact
│   ├── documents.php       # Bibliothèque de documents
│   ├── documents-detail.php# Détail d'un document
│   ├── aide.php            # Page d'aide
│   ├── aide-detail.php     # Détail aide
│   ├── terms.php           # Conditions d'utilisation
│   ├── privacy.php         # Politique de confidentialité
│   ├── sitemap.php         # Plan du site
│   └── 404.php             # Page d'erreur 404
│
├── partials/               # Composants réutilisables
│   ├── header.php          # En-tête HTML (nav, meta, CSS)
│   └── footer.php          # Pied de page (liens, scripts)
│
├── src/                    # Classes PHP
│   ├── autoload.php        # Autoloader des classes
│   ├── Models/             # Modèles de données
│   │   ├── DomaineModel.php
│   │   ├── EntrepriseModel.php
│   │   └── ExperienceModel.php
│   └── Services/           # Services métier
│       ├── AuthService.php
│       └── SecurityService.php
│
├── cache/                  # Cache des filtres (JSON)
├── logs/                   # Logs applicatifs
└── vendor/                 # Dépendances Composer (auto-généré)
```

---

## 9 — Maintenance

### 9.1 — Sauvegarder la base de données

```bash
mysqldump -u annuaire_user -p annuaire_geii > backup_annuaire_$(date +%Y%m%d).sql
```

### 9.2 — Mettre à jour les dépendances

```bash
cd /chemin/vers/Annuaire
composer update --no-dev
```

### 9.3 — Consulter les logs

Les actions administrateur sont enregistrées dans la table `logs_audit`.

```sql
SELECT * FROM logs_audit ORDER BY created_at DESC LIMIT 50;
```

### 9.4 — Nettoyage automatique

Le système supprime automatiquement les expériences dans la corbeille après **1 heure**. Ce nettoyage s'exécute lors de chaque chargement d'une page admin.

---

## 10 — FAQ / Dépannage

### « Les emails ne s'envoient pas »

1. Vérifiez que `MAIL_MODE`, `FROM_EMAIL` et `ADMIN_EMAIL` sont bien renseignés dans `.env`
2. Si vous utilisez le mode `smtp`, vérifiez les identifiants `SMTP_*`
3. Vérifiez que le port SMTP n'est pas bloqué par le pare-feu du serveur
4. Testez avec le mode `phpmail` en premier (plus simple)

### « Erreur 500 au chargement »

1. Vérifiez que `mod_rewrite` est activé dans Apache
2. Vérifiez les identifiants de base de données dans `.env`
3. Consultez les logs Apache : `/var/log/apache2/error.log`

### « Je ne peux pas me connecter à l'admin »

1. Vérifiez que la table `admins` contient au moins un enregistrement
2. Régénérez le mot de passe avec :
   ```bash
   php -r "echo password_hash('NouveauMotDePasse', PASSWORD_DEFAULT);"
   ```
3. Mettez à jour en base :
   ```sql
   UPDATE admins SET password = 'HASH_GENERE' WHERE username = 'admin';
   ```

### « Les pages renvoient une erreur 404 »

1. Vérifiez que `$BASE` est correctement configuré (section 5)
2. Vérifiez la ligne `ErrorDocument 404` dans `.htaccess`
3. Vérifiez que le dossier est bien nommé et accessible

### « Les images/CSS ne se chargent pas »

1. Vérifiez que `$BASE` correspond au chemin réel du projet
2. Videz le cache navigateur (`Ctrl+Shift+R`)

---

## Crédits

Projet réalisé par **ROSSIGNOL Romane**, étudiante BUT GEII — IUT de Lille, Université de Lille.

---

*Dernière mise à jour : Avril 2026*
