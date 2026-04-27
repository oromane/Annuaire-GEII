# 📚 Annuaire des Expériences GEII

Application web de gestion et de consultation des expériences professionnelles (stages et alternances) des étudiants du département **GEII** (Génie Électrique et Informatique Industrielle) - **IUT de Lille, Université de Lille**.

---

## 🎯 Fonctionnalités

- **Annuaire consultable** - Recherche par entreprise, ville, domaine, année, type (stage/alternance)
- **Filtres avancés** - Filtrage géographique par ville et rayon
- **Fiches détaillées** - Poste, missions, outils, technologies utilisées
- **Pages entreprises** - Regroupement des expériences par entreprise
- **Ajout par les étudiants** - Formulaire avec vérification par email
- **Panneau d'administration** - Approbation, édition, suppression des expériences
- **Recherche globale** - Recherche instantanée dans tout le contenu
- **Bibliothèque de documents** - Ressources pour la recherche de stage/alternance
- **Formulaire de contact** - Avec protection anti-spam (honeypot)
- **Responsive design** - Compatible mobile, tablette et desktop
- **Mode sombre / clair** - Thème adaptatif

## 🛠️ Technologies

| Composant | Technologie |
|-----------|-------------|
| Backend | PHP 8.0+ |
| Base de données | MySQL / MariaDB |
| Frontend | HTML5, CSS3 (vanilla), JavaScript |
| Serveur | Apache (mod_rewrite) |
| Dépendances | Composer (PHPMailer) |

## 📦 Installation rapide

```bash
# 1. Cloner le projet
git clone https://github.com/oromane/Annuaire-GEII.git

# 2. Installer les dépendances
cd Annuaire-GEII
composer install --no-dev

# 3. Configurer l'environnement
cp .env.example .env
# Éditez .env avec vos paramètres (BDD, email)

# 4. Importer la base de données
mysql -u votre_user -p annuaire_geii < database.sql

# 5. Créer le compte administrateur
php -r "echo password_hash('VotreMotDePasse', PASSWORD_DEFAULT);"
# Insérez le hash dans la table admins (voir guide)
```

> 📘 **Guide complet** : Consultez le fichier [`GUIDE_DEPLOIEMENT.md`](GUIDE_DEPLOIEMENT.md) pour les instructions détaillées d'installation, de configuration et de maintenance.

## 📁 Structure du projet

```
Annuaire-GEII/
├── admin/          # Panneau d'administration
├── api/            # Backend (connexion BDD, services mail, APIs JSON)
├── assets/         # CSS, JavaScript, images
├── docs/           # Documents PDF (ressources étudiantes)
├── pages/          # Pages publiques
├── partials/       # Header et footer réutilisables
├── src/            # Classes PHP (Models, Services)
├── database.sql    # Dump SQL (structure + données)
├── .env.example    # Template de configuration
└── GUIDE_DEPLOIEMENT.md  # Documentation complète
```

## 🔒 Sécurité

- Requêtes SQL paramétrées (PDO)
- Protection CSRF sur tous les formulaires
- Sessions sécurisées (httponly, samesite, expiration)
- En-têtes de sécurité HTTP
- Fichiers sensibles protégés par `.htaccess`
- Logs d'audit des actions administrateur
- Suppression réversible (corbeille avec délai de 1h)

## 👤 Auteur

**ROSSIGNOL Romane** - Étudiant BUT GEII, IUT de Lille, Université de Lille

---

*Projet réalisé dans le cadre du BUT GEII - 2025/2026*
