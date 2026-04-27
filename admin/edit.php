<?php
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php'; // D'abord le bootstrap qui gère la session
require_login();
$BASE = '/Annuaire';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$mode = $id > 0 ? 'edit' : 'create';
$pageTitle = $mode === 'create' ? 'Ajouter  une  expérience' : 'Modifier  l\'expérience  #' . $id;
$errors = [];

//  ---  Récupération  des  Domaines  pour  le  SELECT  ---
try {
        $stmtDomaines = $pdo->query("SELECT  id,  nom  FROM  domaines  ORDER  BY  nom");
        $domainesList = $stmtDomaines->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
        //  Si  la  table  domaines  n'existe  pas  encore
        $domainesList = [];
        $errors['db_init'] = 'Erreur:  Impossible  de  charger  la  liste  des  domaines.';
        log_action('ERROR', 'Impossible  de  charger  domaines:  ' . $e->getMessage());
}
try {
        $stmtEntreprises = $pdo->query("SELECT nom FROM entreprises ORDER BY nom");
        $entreprisesList = $stmtEntreprises->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
        $entreprisesList = []; // Pas critique, on ne bloque pas
        log_action('ERROR', 'Impossible de charger la datalist entreprises: ' . $e->getMessage());
}

//  ---  LOGIQUE  DE  L'ANNÉE  UNIVERSITAIRE  ---
$current_year = (int) date('Y');
$academic_start_year = (date('n') >= 7) ? $current_year : $current_year - 1;
$default_annee_start = (string) $academic_start_year;
$default_annee_display = $academic_start_year . '-' . ($academic_start_year + 1);

//  ---  CHAMPS  VALEURS  PAR  DÉFAUT  ---
$vals = [
        'etudiant_nom' => '',
        'etudiant_prenom' => '',
        'entreprise_nom' => '',
        'type' => 'Stage',
        'poste' => '',
        'ville' => '',
        'annee' => $default_annee_start,
        'annee_display' => $default_annee_display,
        'missions' => '',
        'outils' => '',
        'entreprise_adresse' => '',
        'entreprise_contact' => '',
        'entreprise_phone' => '',
        'etudiant_email' => '',
        'etudiant_linkedin' => '',
        'entreprise_linkedin' => '',
        'is_approved' => '1',

        //  NOUVEAU  :  pour  la  BDD  normalisée
        'entreprise_id' => null,
        'domaine_id' => null,
        'domaine_nom' => '',  //  Stocke  le  nom  du  domaine  pour  le  HTML  SELECT
];
$row = null;

//  If  editing,  fetch  existing  data
if ($mode === 'edit') {
        //  JOINTURE  pour  récupérer  les  noms  si  l'ID  est  défini  (pour  affichage)
        $stmt = $pdo->prepare("
                SELECT  
                        e.*,  
                        d.nom  AS  domaine_nom,
                        ent.nom  AS  entreprise_nom_fk,
                        

                        ent.adresse AS entreprise_adresse_fk,
                        ent.contact_phone AS entreprise_phone_fk,
                        ent.contact_email AS entreprise_contact_fk,
                        ent.linkedin_url AS entreprise_linkedin_fk

                FROM  experiences  e
                LEFT  JOIN  domaines  d  ON  e.domaine_id  =  d.id
                LEFT  JOIN  entreprises  ent  ON  e.entreprise_id  =  ent.id
                WHERE  e.id  =  :id
        ");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        if (!$row) {
                flash_message('Expérience  introuvable.', 'error');
                redirect('index.php');
        }

        //  Remplissage  des  valeurs  pour  l'affichage
        foreach (array_keys($vals) as $key) {
                if (isset($row[$key])) {
                        $vals[$key] = (string) $row[$key];
                }
        }

        //  Correction  des  valeurs  normalisées  pour  le  formulaire
        $vals['domaine_nom'] = $row['domaine_nom'] ?? $row['domaine'] ?? '';
        $vals['entreprise_nom'] = $row['entreprise_nom_fk'] ?? $row['entreprise_nom'] ?? '';


        $vals['entreprise_adresse'] = $row['entreprise_adresse_fk'] ?? $row['entreprise_adresse'] ?? '';
        $vals['entreprise_contact'] = $row['entreprise_contact_fk'] ?? $row['entreprise_contact'] ?? '';
        $vals['entreprise_phone'] = $row['entreprise_phone_fk'] ?? $row['entreprise_phone'] ?? '';
        $vals['entreprise_linkedin'] = $row['entreprise_linkedin_fk'] ?? $row['entreprise_linkedin'] ?? '';

        $start_year = (int) $vals['annee'];
        $vals['annee_display'] = $start_year . '-' . ($start_year + 1);
}

//  Handle  form  submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        //  1.  SÉCURITÉ  :  VÉRIFICATION  CSRF
        verify_csrf('edit.php?id=' . $id);

        //  Remplissage  des  $vals  à  partir  de  $_POST
        foreach (array_keys($vals) as $key) {
                //  Ignorer  les  chemins  de  fichiers,  les  IDs,  et  l'année  (qui  est  traitée)
                if (in_array($key, ['entreprise_id', 'domaine_id', 'domaine_nom', 'annee']))
                        continue;
                $vals[$key] = trim((string) ($_POST[$key] ?? $vals[$key]));
        }

        $vals['domaine_nom'] = trim((string) ($_POST['domaine'] ?? ''));
        $vals['entreprise_nom'] = trim((string) ($_POST['entreprise_nom'] ?? ''));

        //  ---  Validation  ---
        if ($vals['etudiant_nom'] === '')
                $errors['etudiant_nom'] = 'Nom  requis';
        if ($vals['entreprise_nom'] === '')
                $errors['entreprise_nom'] = 'Nom  entreprise  requis';
        //  ...  (Reste  des  validations)  ...

        //  Validation  Année  (reste  le  même)
        $annee = null;
        $annee_match = [];
        if (preg_match('/^(\d{4})\s*[–-]\s*(\d{4})$/', $vals['annee_display'], $annee_match)) {
                $start_year = (int) $annee_match[1];
                $end_year = (int) $annee_match[2];
                if ($end_year === $start_year + 1 && $start_year >= 2000 && $start_year <= (int) date('Y') + 5) {
                        $annee = $start_year;
                }
        }
        if ($annee === null) {
                $errors['annee_display'] = 'Format  invalide  (ex:  2024-2025)';
        }

        //  ---  LOGIQUE  CRUCIALE  DE  LIAISON  D'ID  (entreprise  et  domaine)  ---
        if (empty($errors)) {

                //  2.  GESTION  DU  DOMAINE  (Créer  si  besoin,  obtenir  l'ID)
                $domaineNom = $vals['domaine_nom'];
                if (!empty($domaineNom)) {
                        $stmt = $pdo->prepare("SELECT  id  FROM  domaines  WHERE  nom  =  :nom");
                        $stmt->execute([':nom' => $domaineNom]);
                        $domaineId = $stmt->fetchColumn();
                        if (!$domaineId) {
                                $stmt = $pdo->prepare("INSERT  INTO  domaines  (nom)  VALUES  (:nom)");
                                $stmt->execute([':nom' => $domaineNom]);
                                $domaineId = $pdo->lastInsertId();
                                log_action('CREATE_DOMAIN', "Nouveau  domaine  créé:  $domaineNom");
                        }
                        $vals['domaine_id'] = $domaineId;
                }

                //  3.  GESTION  DE  L'ENTREPRISE  (Créer  si  besoin,  obtenir  l'ID)
                $entrepriseNom = $vals['entreprise_nom'];
                if (!empty($entrepriseNom)) {
                        //  Tente  de  trouver  l'ID
                        $stmt = $pdo->prepare("SELECT  id  FROM  entreprises  WHERE  nom  =  :nom");
                        $stmt->execute([':nom' => $entrepriseNom]);
                        $entrepriseId = $stmt->fetchColumn();

                        //  Si  l'entreprise  n'existe  PAS,  on  la  crée  avec  les  infos  du  formulaire
                        if (!$entrepriseId) {
                                $stmt = $pdo->prepare("
                                        INSERT  INTO  entreprises  (nom,  adresse,  ville,  contact_phone,  linkedin_url)
                                        VALUES  (:nom,  :addr,  :ville,  :phone,  :linkedin)
                                ");
                                // PARAMÈTRES POUR L'INSERT (avec :nom)
                                $insertParams = [
                                        ':nom' => $entrepriseNom,
                                        ':addr' => $vals['entreprise_adresse'] ?: null,
                                        ':ville' => $vals['ville'] ?: null,
                                        ':phone' => $vals['entreprise_phone'] ?: null,
                                        ':linkedin' => $vals['entreprise_linkedin'] ?: null,
                                ];
                                $stmt->execute($insertParams); // <- Ligne 178
                                $entrepriseId = $pdo->lastInsertId();
                                log_action('CREATE_ENT', "Nouvelle  entreprise  créée:  $entrepriseNom  (ID:  $entrepriseId)");
                        } else {
                                $stmt = $pdo->prepare("
                                UPDATE entreprises SET
                                    adresse = :addr,
                                    ville = :ville,
                                    contact_phone = :phone,
                                    linkedin_url = :linkedin
                                WHERE id = :id
                            ");
                                // PARAMÈTRES POUR L'UPDATE (sans :nom, avec :id)
                                $updateParams = [
                                        ':addr' => $vals['entreprise_adresse'] ?: null,
                                        ':ville' => $vals['ville'] ?: null,
                                        ':phone' => $vals['entreprise_phone'] ?: null,
                                        ':linkedin' => $vals['entreprise_linkedin'] ?: null,
                                        ':id' => $entrepriseId
                                ];
                                $stmt->execute($updateParams);
                                log_action('UPDATE_ENT', "Entreprise (ID: $entrepriseId) mise à jour via edit.php.");
                        }
                        $vals['entreprise_id'] = $entrepriseId;
                }

                //  ---  Database  Operation  ---
                try {
                        //  PARAMÈTRES  FINAUX  pour  la  BDD (CORRIGÉS)
                        $params = [
                                ':enom' => $vals['etudiant_nom'],
                                ':epr' => $vals['etudiant_prenom'],
                                ':ent_id' => $vals['entreprise_id'],  //  CLÉ  ÉTRANGÈRE
                                ':type' => $vals['type'],
                                ':poste' => $vals['poste'],
                                ':dom_id' => $vals['domaine_id'],  //  CLÉ  ÉTRANGÈRE
                                ':ville' => $vals['ville'] ?: null,
                                ':annee' => $annee,
                                ':missions' => $vals['missions'] ?: null,
                                ':outils' => $vals['outils'] ?: null,
                                ':etu_email' => $vals['etudiant_email'] ?: null,
                                ':etu_link' => $vals['etudiant_linkedin'] ?: null,
                                ':is_approved' => isset($_POST['is_approved']) ? 1 : 0,
                                //  On  garde  les  anciens  champs  texte  pour  les  expériences  non  encore  migrées
                                ':ent_nom_txt' => $vals['entreprise_nom'],
                                ':domaine_txt' => $vals['domaine_nom'],
                        ];

                        if ($mode === 'create') {

                                $sql = "INSERT  INTO  experiences  (
                                        etudiant_nom,  etudiant_prenom,  entreprise_id,  entreprise_nom,  type,  domaine_id,  domaine,  poste,  ville,  annee,  missions,  outils,  etudiant_email,  etudiant_linkedin,  is_approved,  created_at
                                )  VALUES  (
                                        :enom,  :epr,  :ent_id,  :ent_nom_txt,  :type,  :dom_id,  :domaine_txt,  :poste,  :ville,  :annee,  :missions,  :outils,  :etu_email,  :etu_link,  :is_approved,  NOW()
                                )";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute($params);
                                $newId = $pdo->lastInsertId();

                                log_action('CREATE', "Expérience  (ID:  $newId)  créée  par  admin.");
                                flash_message('Expérience  ajoutée  avec  succès  (ID:  ' . $newId . ').', 'success');

                        } else {

                                $sql = "UPDATE  experiences  SET
                                                etudiant_nom=:enom,  etudiant_prenom=:epr,  entreprise_id=:ent_id,  entreprise_nom=:ent_nom_txt,  type=:type,  domaine_id=:dom_id,  domaine=:domaine_txt,  poste=:poste,
                                                ville=:ville,  annee=:annee,  missions=:missions,  outils=:outils,  
                                                etudiant_email=:etu_email,  etudiant_linkedin=:etu_link, 
                                                is_approved=:is_approved,  updated_at=NOW()
                                        WHERE  id=:id";
                                $stmt = $pdo->prepare($sql);
                                $params[':id'] = $id;
                                $stmt->execute($params);

                                log_action('EDIT', "Expérience  (ID:  $id)  modifiée  par  admin.");
                                flash_message('Expérience  #' . $id . '  modifiée  avec  succès.', 'success');
                        }
                        redirect('index.php');

                } catch (Throwable $e) {
                        log_action('ERROR', "Échec  DB  sur  edit.php  (Mode:  $mode,  ID:  $id):  " . $e->getMessage());
                        $errors['db'] = 'Erreur  base  de  données:  ' . $e->getMessage();
                }
        }
}
?>
<!doctype html>
<html lang="fr" data-theme="<?php echo htmlspecialchars($_COOKIE['theme'] ?? 'light'); ?>">

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,  initial-scale=1">
        <title>Admin - <?= e($pageTitle) ?></title>
        <link rel="stylesheet" href="../assets/css/style.css?v=edit-final-v3">
        <style>
                .error-text {
                        color: #D9534F;
                        font-size: 0.85rem;
                        font-weight: 600;
                        margin-top: 4px;
                }

                .field-checkbox {
                        display: flex;
                        align-items: center;
                        gap: 8px;
                        margin-bottom: 10px;
                }

                .field-checkbox label {
                        margin-bottom: 0;
                }

                .field-checkbox input {
                        width: auto;
                        height: auto;
                }
        </style>
</head>

<body data-theme="<?php echo htmlspecialchars($_COOKIE['theme'] ?? 'light'); ?>">

        <div class="header-spacer"></div>

        <main class="admin-main">
                <div class="admin-header">
                        <div>
                                <h1><?= e($pageTitle) ?></h1>
                                <p class="subtitle">Gestion des expériences - Connecté en tant que :
                                        <?= e($_SESSION['admin_username']); ?>
                                </p>
                        </div>

                        <!-- TOGGLE THEME -->
                        <div class="theme-toggle">
                                <input type="checkbox" id="themeSwitch" class="theme-switch-input"
                                        aria-label="Basculer clair/sombre" <?= ($_COOKIE['theme'] ?? 'light') === 'dark' ? 'checked' : '' ?>>
                                <label for="themeSwitch" class="toggle-track new-toggle-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" fill="currentColor"
                                                stroke-linecap="round" viewBox="0 0 32 32" class="toggle-svg">
                                                <clipPath id="skiper-btn-3">
                                                        <path class="toggle-clip-path"
                                                                d="M0-11h25a1 1 0 0017 13v30H0Z" />
                                                </clipPath>
                                                <g clip-path="url(#skiper-btn-3)">
                                                        <circle class="toggle-orb" cx="16" cy="16" r="8" />
                                                        <g class="toggle-rays" stroke="currentColor" stroke-width="1.5">
                                                                <path
                                                                        d="M18.3 3.2c0 1.3-1 2.3-2.3 2.3s-2.3-1-2.3-2.3S14.7.9 16 .9s2.3 1 2.3 2.3zm-4.6 25.6c0-1.3 1-2.3 2.3-2.3s2.3 1 2.3 2.3-1 2.3-2.3 2.3-2.3-1-2.3-2.3zm15.1-10.5c-1.3 0-2.3-1-2.3-2.3s1-2.3 2.3-2.3 2.3 1 2.3 2.3-1 2.3-2.3 2.3zM3.2 13.7c1.3 0 2.3 1 2.3 2.3s-1 2.3-2.3 2.3S.9 17.3.9 16s1-2.3 2.3-2.3zm5.8-7C9 7.9 7.9 9 6.7 9S4.4 8 4.4 6.7s1-2.3 2.3-2.3S9 5.4 9 6.7zm16.3 21c-1.3 0-2.3-1-2.3-2.3s1-2.3 2.3-2.3 2.3 1 2.3 2.3-1 2.3-2.3 2.3zm2.4-21c0 1.3-1 2.3-2.3 2.3S23 7.9 23 6.7s1-2.3 2.3-2.3 2.4 1 2.4 2.3zM6.7 23C8 23 9 24 9 25.3s-1 2.3-2.3 2.3-2.3-1-2.3-2.3 1-2.3 2.3-2.3z" />
                                                        </g>
                                                </g>
                                        </svg>
                                </label>
                        </div>

                        <a class="btn-sm btn-danger" href="logout.php">Déconnexion</a>
                </div>

                <section class="card  edit-form-card">
                        <div class="card-body">

                                <?php if (!empty($errors['csrf']) || !empty($errors['db'])): ?>
                                        <div class="alert  error" role="alert">
                                                <?= e($errors['csrf'] ?? $errors['db'] ?? 'Une  erreur  est  survenue.') ?>
                                        </div>
                                <?php endif; ?>

                                <form method="post" novalidate class="edit-form">
                                        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

                                        <h2 class="form-title"><?= e($pageTitle) ?></h2>
                                        <p class="form-description">Gérez les informations de l'expérience, de
                                                l'étudiant, de l'entreprise et les documents associés.</p>


                                        <div class="form-grid grid-cols-2">
                                                <div class="field">
                                                        <label for="etudiant_nom">Nom étudiant <span
                                                                        class="req">*</span></label>
                                                        <input id="etudiant_nom" name="etudiant_nom"
                                                                value="<?= e($vals['etudiant_nom']) ?>" required>
                                                        <?php if (isset($errors['etudiant_nom'])): ?>
                                                                <div class="error-text"><?= e($errors['etudiant_nom']) ?></div>
                                                        <?php endif; ?>
                                                </div>
                                                <div class="field">
                                                        <label for="etudiant_prenom">Prénom étudiant <span
                                                                        class="req">*</span></label>
                                                        <input id="etudiant_prenom" name="etudiant_prenom"
                                                                value="<?= e($vals['etudiant_prenom']) ?>" required>
                                                        <?php if (isset($errors['etudiant_prenom'])): ?>
                                                                <div class="error-text"><?= e($errors['etudiant_prenom']) ?>
                                                                </div><?php endif; ?>
                                                </div>
                                                <div class="field">
                                                        <label for="entreprise_nom">Entreprise <span
                                                                        class="req">*</span></label>
                                                        <input id="entreprise_nom" name="entreprise_nom"
                                                                list="company-list"
                                                                value="<?= e($vals['entreprise_nom']) ?>" required>
                                                        <datalist id="company-list">
                                                                <?php foreach ($entreprisesList as $entreprise): ?>
                                                                        <option value="<?= e($entreprise['nom']) ?>">
                                                                        <?php endforeach; ?>
                                                        </datalist>
                                                        <?php if (isset($errors['entreprise_nom'])): ?>
                                                                <div class="error-text"><?= e($errors['entreprise_nom']) ?>
                                                                </div><?php endif; ?>
                                                </div>
                                                <div class="field">
                                                        <label for="poste">Poste <span class="req">*</span></label>
                                                        <input id="poste" name="poste" value="<?= e($vals['poste']) ?>"
                                                                required>
                                                        <?php if (isset($errors['poste'])): ?>
                                                                <div class="error-text"><?= e($errors['poste']) ?></div>
                                                        <?php endif; ?>
                                                </div>
                                                <div class="field">
                                                        <label for="type">Type <span class="req">*</span></label>
                                                        <select id="type" name="type">
                                                                <option <?= $vals['type'] === 'Stage' ? 'selected' : '' ?>>
                                                                        Stage</option>
                                                                <option <?= $vals['type'] === 'Alternance' ? 'selected' : '' ?>>Alternance</option>
                                                        </select>
                                                        <?php if (isset($errors['type'])): ?>
                                                                <div class="error-text"><?= e($errors['type']) ?></div>
                                                        <?php endif; ?>
                                                </div>
                                                <div class="field">
                                                        <label for="domaine">Domaine</label>
                                                        <input id="domaine" name="domaine" list="domain-list"
                                                                value="<?= e($vals['domaine_nom']) ?>"
                                                                placeholder="Taper ou choisir un domaine...">
                                                        <datalist id="domain-list">
                                                                <?php foreach ($domainesList as $domaine): ?>
                                                                        <option value="<?= e($domaine['nom']) ?>">
                                                                        <?php endforeach; ?>
                                                        </datalist>
                                                </div>
                                                <div class="field">
                                                        <label for="ville">Ville</label>
                                                        <input id="ville" name="ville" value="<?= e($vals['ville']) ?>">
                                                </div>

                                                <div class="field">
                                                        <label for="annee_display">Année universitaire <span
                                                                        class="req">*</span></label>
                                                        <input id="annee_display" name="annee_display" type="text"
                                                                value="<?= e($vals['annee_display']) ?>" required
                                                                placeholder="AAAA-AAAA">
                                                        <div class="help">Format : AAAA-AAAA.</div>
                                                        <?php if (isset($errors['annee_display'])): ?>
                                                                <div class="error-text"><?= e($errors['annee_display']) ?></div>
                                                        <?php endif; ?>
                                                </div>
                                        </div>


                                        <div class="form-grid grid-cols-2">
                                                <div class="field">
                                                        <label for="entreprise_adresse">Adresse</label>
                                                        <input id="entreprise_adresse" name="entreprise_adresse"
                                                                value="<?= e($vals['entreprise_adresse']) ?>">
                                                </div>
                                                <div class="field">
                                                        <label for="entreprise_contact">Contact (Email ou
                                                                Tuteur)</label>
                                                        <input id="entreprise_contact" name="entreprise_contact"
                                                                value="<?= e($vals['entreprise_contact']) ?>"
                                                                placeholder="Email ou Tél">
                                                </div>
                                                <div class="field">
                                                        <label for="entreprise_phone">Numéro de téléphone</label>
                                                        <input id="entreprise_phone" name="entreprise_phone" type="tel"
                                                                value="<?= e($vals['entreprise_phone']) ?>"
                                                                placeholder="02 03 04 05 06 ">
                                                </div>

                                                <div class="field">
                                                        <label for="entreprise_linkedin">LinkedIn Entreprise</label>
                                                        <input id="entreprise_linkedin" name="entreprise_linkedin"
                                                                type="url"
                                                                value="<?= e($vals['entreprise_linkedin']) ?>"
                                                                placeholder="https://linkedin.com/company/...">
                                                </div>
                                        </div>

                                        <div class="form-grid grid-cols-2">
                                                <div class="field">
                                                        <label for="etudiant_email">Email Étudiant</label>
                                                        <input id="etudiant_email" name="etudiant_email" type="email"
                                                                value="<?= e($vals['etudiant_email']) ?>"
                                                                placeholder="prenom.nom.etu@univ-lille.fr">
                                                </div>
                                                <div class="field">
                                                        <label for="etudiant_linkedin">LinkedIn Étudiant</label>
                                                        <input id="etudiant_linkedin" name="etudiant_linkedin"
                                                                type="url" value="<?= e($vals['etudiant_linkedin']) ?>"
                                                                placeholder="https://linkedin.com/in/...">
                                                </div>
                                        </div>

                                        <div class="form-grid grid-cols-1">
                                                <div class="field">
                                                        <label for="missions">Missions Principales</label>
                                                        <textarea id="missions" name="missions"
                                                                rows="6"><?= e($vals['missions']) ?></textarea>
                                                </div>
                                        </div>

                                        <div class="form-grid grid-cols-1">
                                                <div class="field">
                                                        <label for="outils">Outils (séparés par virgule)</label>
                                                        <input id="outils" name="outils"
                                                                value="<?= e($vals['outils']) ?>">
                                                        <div class="help">Ex: TIA Portal, LabVIEW, Multimètre...</div>
                                                </div>
                                        </div>



                                        <div class="form-grid grid-cols-1">
                                                <div class="field-checkbox">
                                                        <input type="checkbox" id="is_approved" name="is_approved"
                                                                value="1" <?= $vals['is_approved'] === '1' ? 'checked' : '' ?>>
                                                        <label for="is_approved">Approuver cette expérience (visible
                                                                publiquement)</label>
                                                </div>
                                        </div>

                                        <div class="actions edit-form-actions">
                                                <a class="btn outline" href="index.php">Retour à la liste</a>
                                                <button class="btn primary" type="submit">💾 Enregistrer</button>
                                        </div>
                                </form>
                        </div>
                </section>
        </main>
        <?php include dirname(__DIR__) . '/partials/footer.php';  //  Include  main  footer  ?>
</body>

</html>