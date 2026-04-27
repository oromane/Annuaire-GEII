<?php
// api/experiences.php - Gère GET (détail) et POST (création)
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
ini_set('display_errors', '0');
error_reporting(E_ALL);

$debug = false;

require_once __DIR__ . '/connexion.php';

try {
    $pdo = get_pdo();
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Connexion BD impossible.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

// ===========================================
// METHODE GET : Afficher les détails
// ===========================================
if ($method === 'GET') {
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'ID manquant ou invalide pour GET.'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    try {
        // Récupération avec jointures pour les détails normalisés
        $stmt = $pdo->prepare("
            SELECT 
                e.*, 
                ent.adresse AS entreprise_adresse_fk,
                ent.contact_phone AS entreprise_phone_fk,
                ent.contact_email AS entreprise_email_fk,
                ent.site_web AS entreprise_site_fk,
                ent.linkedin_url AS entreprise_linkedin_fk,
                d.nom AS domaine_nom_fk
            FROM experiences e
            LEFT JOIN entreprises ent ON e.entreprise_id = ent.id
            LEFT JOIN domaines d ON e.domaine_id = d.id
            WHERE e.id = :id AND e.deleted_at IS NULL
            LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        $exp = $stmt->fetch();

        if (!$exp) {
            http_response_code(404);
            echo json_encode(['error' => 'Expérience introuvable.'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Fusion des données pour compatibilité
        $exp['domaine'] = $exp['domaine_nom_fk'] ?? $exp['domaine'];
        $exp['entreprise_adresse'] = $exp['entreprise_adresse_fk'] ?? '';
        $exp['entreprise_phone'] = $exp['entreprise_phone_fk'] ?? $exp['entreprise_phone'];
        $exp['entreprise_email'] = $exp['entreprise_email_fk'] ?? $exp['entreprise_email'];
        $exp['entreprise_linkedin'] = $exp['entreprise_linkedin_fk'] ?? $exp['entreprise_linkedin'];

        $comments = [];
        try {
            $stmt = $pdo->prepare("SELECT comment, author, author_email, created_at FROM experience_comments WHERE experience_id = :id AND is_approved = 1 ORDER BY created_at DESC");
            $stmt->execute([':id' => $id]);
            $comments = $stmt->fetchAll();
        } catch (Throwable $e2) {
            if ($debug) {
                $exp['_comments_error'] = $e2->getMessage();
            }
        }
        $exp['comments'] = $comments;

        echo json_encode($exp, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    } catch (Throwable $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la récupération (GET).'] + ($debug ? ['debug' => $e->getMessage()] : []), JSON_UNESCAPED_UNICODE);
    }

    // ============================================
// METHODE POST : Créer une expérience (Normalisation)
// ============================================
} elseif ($method === 'POST') {
    if (!empty($_POST['id']) || !empty($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Un ID ne doit pas être fourni pour une création.'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // --- HONEYPOT ANTI-SPAM ---
    if (!empty($_POST['website'])) {
        // Pseudo-succès silencieux pour tromper les bots
        http_response_code(201);
        echo json_encode([
            'success' => true,
            'id' => 0,
            'message' => 'Enregistrement réussi ! Votre expérience est en attente de modération.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Validation serveur (simple)
    $errors = [];
    $required = ['etudiant_nom', 'etudiant_prenom', 'entreprise_nom', 'type', 'poste', 'annee'];
    $data = [];
    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            $errors[$key] = "Ce champ est requis.";
        }
        $data[$key] = trim($_POST[$key] ?? '');
    }

    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode(['error' => $errors], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // --- CHAMPS OPTIONNELS (Collecte) ---
    $optionalFields = [
        'domaine',
        'ville',
        'missions',
        'outils',
        'entreprise_adresse',
        'entreprise_contact',
        'entreprise_phone',
        'entreprise_linkedin',
        'etudiant_email',
        'etudiant_linkedin'
    ];
    foreach ($optionalFields as $key) {
        $data[$key] = isset($_POST[$key]) ? trim($_POST[$key]) : null;
    }

    // --- LOGIQUE D'INSERTION DANS LES TABLES NORMALISÉES ---

    // 1. GESTION DU DOMAINE (Trouver ou Créer)
    $domaineId = null;
    $domaineNom = $data['domaine'] ?? '';

    if (!empty($domaineNom)) {
        $stmt = $pdo->prepare("SELECT id FROM domaines WHERE nom = :nom");
        $stmt->execute([':nom' => $domaineNom]);
        $domaineId = $stmt->fetchColumn();

        if (!$domaineId) {
            $stmt = $pdo->prepare("INSERT INTO domaines (nom) VALUES (:nom)");
            $stmt->execute([':nom' => $domaineNom]);
            $domaineId = $pdo->lastInsertId();
        }
    }
    $data['domaine_id'] = $domaineId;

    // 2. GESTION DE L'ENTREPRISE (Trouver ou Créer)
    $entrepriseId = null;
    $entrepriseNom = $data['entreprise_nom'];

    if (!empty($entrepriseNom)) {
        $stmt = $pdo->prepare("SELECT id FROM entreprises WHERE nom = :nom");
        $stmt->execute([':nom' => $entrepriseNom]);
        $entrepriseId = $stmt->fetchColumn();

        if (!$entrepriseId) {
            // Création de l'entreprise, en utilisant les champs disponibles
            $stmt = $pdo->prepare("
                INSERT INTO entreprises (nom, adresse, ville, contact_email, contact_phone, linkedin_url)
                VALUES (:nom, :addr, :ville, :email, :phone, :linkedin)
            ");
            $stmt->execute([
                ':nom' => $entrepriseNom,
                ':addr' => $data['entreprise_adresse'] ?? null,
                ':ville' => $data['ville'] ?? null,
                ':email' => $data['entreprise_contact'] ?? null,
                ':phone' => $data['entreprise_phone'] ?? null,
                ':linkedin' => $data['entreprise_linkedin'] ?? null,
            ]);
            $entrepriseId = $pdo->lastInsertId();
        }
    }
    $data['entreprise_id'] = $entrepriseId;

    // --- Insertion dans la table experiences (Utilise les IDs) ---
    try {
        // --- LOGIQUE DE RÉUTILISATION D'ID (GAP FILLING) ---
        $newIdToUse = null;

        // 1. Vérifier si l'ID 1 est libre
        $stmtCheck1 = $pdo->query("SELECT id FROM experiences WHERE id = 1");
        if ($stmtCheck1->rowCount() === 0) {
            $newIdToUse = 1;
        } else {
            // 2. Chercher le premier trou (Gap)
            $sqlGap = "SELECT t1.id + 1 AS gap_id 
                       FROM experiences t1 
                       LEFT JOIN experiences t2 ON t1.id + 1 = t2.id 
                       WHERE t2.id IS NULL 
                       ORDER BY t1.id ASC LIMIT 1";
            $stmtGap = $pdo->query($sqlGap);
            $gapRow = $stmtGap->fetch();
            if ($gapRow) {
                $newIdToUse = $gapRow['gap_id'];
            }
        }

        // Génération du token de vérification
        $emailToken = bin2hex(random_bytes(32));

        $sql = "INSERT INTO experiences (";
        if ($newIdToUse)
            $sql .= "id, ";
        $sql .= "   etudiant_nom, etudiant_prenom, etudiant_email, etudiant_linkedin,
                    entreprise_id, entreprise_nom, 
                    type, domaine_id, domaine, poste, ville, annee, 
                    missions, outils, 
                    created_at, is_approved, email_verification_token
                ) VALUES (";
        if ($newIdToUse)
            $sql .= ":forced_id, ";
        $sql .= "   :etudiant_nom, :etudiant_prenom, :etudiant_email, :etudiant_linkedin,
                    :entreprise_id, :entreprise_nom, 
                    :type, :domaine_id, :domaine, :poste, :ville, :annee,
                    :missions, :outils,
                    NOW(), 0, :token
                )";
        $stmt = $pdo->prepare($sql);

        // --- Bind des paramètres ---
        $paramsToBind = [
            ':etudiant_nom' => $data['etudiant_nom'],
            ':etudiant_prenom' => $data['etudiant_prenom'],
            ':etudiant_email' => $data['etudiant_email'] ?? null,
            ':etudiant_linkedin' => $data['etudiant_linkedin'] ?? null,

            ':entreprise_id' => $data['entreprise_id'],
            ':entreprise_nom' => $data['entreprise_nom'],
            ':domaine_id' => $data['domaine_id'],
            ':domaine' => $data['domaine'] ?? null,

            ':type' => $data['type'],
            ':poste' => $data['poste'],
            ':ville' => $data['ville'] ?? null,
            ':annee' => $data['annee'],
            ':missions' => $data['missions'] ?? null,
            ':outils' => $data['outils'] ?? null,

            ':token' => $emailToken
        ];

        if ($newIdToUse) {
            $paramsToBind[':forced_id'] = $newIdToUse;
        }

        $stmt->execute($paramsToBind);

        // Si on n'a pas forcé d'ID, on récupère le lastInsertId
        $newId = $newIdToUse ? $newIdToUse : $pdo->lastInsertId();

        // --- ENVOI DES EMAILS (Verification + Notification Admin) ---
        $msg = ' Enregistrement réussi ! Votre expérience est en attente de modération.';

        try {
            require_once __DIR__ . '/../api/MailService.php';
            $mailer = new MailService();

            // 1. Verification Stagiaire
            if (!empty($data['etudiant_email'])) {
                $mailer->sendVerificationEmail($data['etudiant_email'], $data['etudiant_prenom'], $emailToken);
                $msg = ' Enregistrement réussi ! Un email de vérification vous a été envoyé.';
            }

            // 2. Notification Admin
            $mailer->sendAdminNotification($data['etudiant_nom'] . ' ' . $data['etudiant_prenom'], $data['entreprise_nom']);

        } catch (Throwable $eMail) {
            // On ignore l'erreur d'envoi d'email pour ne pas bloquer l'UX, mais on pourrait loguer si besoin.
            // Sur localhost sans SMTP, cela échouera souvent.
            // On ajoute une petite note discrète dans le message de succès si nécessaire, 
            // ou on laisse juste "Enregistrement réussi".
            if ($debug) {
                $msg .= " (Info Debug : Email non envoyé : " . $eMail->getMessage() . ")";
            }
        }

        http_response_code(201); // Created
        echo json_encode([
            'success' => true,
            'id' => $newId,
            'message' => $msg
        ], JSON_UNESCAPED_UNICODE);

    } catch (Throwable $e) {
        http_response_code(500);
        // Affiche l'erreur SQL détaillée si on est en mode debug
        echo json_encode(['error' => "Erreur lors de l'insertion en base de données."] + ($debug ? ['debug' => $e->getMessage()] : []), JSON_UNESCAPED_UNICODE);
    }

} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Méthode non autorisée.'], JSON_UNESCAPED_UNICODE);
}