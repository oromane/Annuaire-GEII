<?php
/**
 * api/annuaire.php
 * Gère la liste paginée (avec JOINs), les contacts et les métadonnées.
 */

declare(strict_types=1);

header("Access-Control-Allow-Origin: *");
$debug = isset($_GET['debug']);
ini_set('display_errors', $debug ? '1' : '0');
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

require_once __DIR__ . '/connexion.php';

try {
    $pdo = get_pdo();
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Connexion BD impossible.'] + ($debug ? ['debug' => $e->getMessage()] : []), JSON_UNESCAPED_UNICODE);
    exit;
}

// CONTACTS (Liste exhaustive des entreprises)
if (isset($_GET['contacts_only']) && $_GET['contacts_only'] === 'true') {
    try {
        $limit = max(1, min(50, (int) ($_GET['limit'] ?? 50)));
        $is_random = isset($_GET['random']) && $_GET['random'] === 'true';

        $orderBy = 'ORDER BY entreprise_nom';
        $limitClause = $is_random ? "LIMIT {$limit}" : '';


        $sql = "
            (
                -- 1. Récupère les entreprises de l'Annuaire GEII (Legacy table)
                SELECT
                    T1.Nom_Societe AS entreprise_nom,
                    T1.Ville AS ville,
                    T1.Classification_GEII AS domaine,
                    NULL AS entreprise_phone,
                    NULL AS entreprise_email,
                    NULL AS entreprise_site,
                    NULL AS entreprise_linkedin,
                    T1.Adresse_1 AS entreprise_adresse,
                    0 AS total_experiences 
                FROM
                    annuaire_geii T1
            )
            UNION
            (
                -- 2. Récupère les entreprises uniques de la table experiences (Nouvelle table)
                SELECT
                    e.entreprise_nom,
                    MAX(e.ville) AS ville,
                    -- On prend le nom du domaine lié, ou le texte saisi s'il n'y a pas de lien
                    MAX(COALESCE(d.nom, e.domaine)) AS domaine,
                    MAX(ent.contact_phone) AS entreprise_phone,
                    MAX(ent.contact_email) AS entreprise_email,
                    MAX(ent.site_web) AS entreprise_site,
                    MAX(ent.linkedin_url) AS entreprise_linkedin,
                    MAX(ent.adresse) AS entreprise_adresse,
                    COUNT(e.id) AS total_experiences
                FROM
                    experiences e
                LEFT JOIN entreprises ent ON e.entreprise_id = ent.id
                LEFT JOIN domaines d ON e.domaine_id = d.id
                WHERE
                    e.is_approved = 1 
                GROUP BY
                    e.entreprise_nom
            )
            {$orderBy}
            {$limitClause}
        ";

        $st = $pdo->prepare($sql);
        $st->execute();
        $companies = $st->fetchAll();

        // Si aléatoire est demandé, on mélange le résultat final de l'UNION
        if ($is_random) {
            shuffle($companies);
            $companies = array_slice($companies, 0, $limit);
        }

        echo json_encode($companies, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
        exit;
    } catch (Throwable $e) {
        http_response_code(500);
        $debugInfo = $debug ? ['debug' => $e->getMessage()] : [];
        echo json_encode(['error' => 'Erreur lors de la récupération des contacts. Détail: ' . $e->getMessage()] + $debugInfo, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

if (isset($_GET['meta'])) {

    $cacheDir = __DIR__ . '/../cache';
    $cacheFile = $cacheDir . '/filters.json';
    $cacheTtl = 86400;
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTtl)) {
        readfile($cacheFile);
        exit;
    }
    try {
        // On récupère les domaines à la fois de la table domaine (via FK) et du champ texte (nouveaux imports)
        $domainesSql = "
            SELECT DISTINCT nom FROM (
                SELECT d.nom FROM domaines d JOIN experiences e ON e.domaine_id = d.id WHERE e.is_approved = 1
                UNION
                SELECT domaine AS nom FROM experiences WHERE is_approved = 1 AND domaine IS NOT NULL AND domaine <> ''
            ) AS combined WHERE nom IS NOT NULL AND nom <> '' ORDER BY nom
        ";
        $domaines = $pdo->query($domainesSql)->fetchAll(PDO::FETCH_COLUMN);
        
        $villes = $pdo->query("SELECT DISTINCT ville FROM experiences WHERE is_approved = 1 AND ville IS NOT NULL AND ville <> '' ORDER BY ville")->fetchAll(PDO::FETCH_COLUMN);
        
        // On récupère les années (format string pour supporter 2025-2027)
        $annees = $pdo->query("SELECT DISTINCT annee FROM experiences WHERE is_approved = 1 ORDER BY annee DESC")->fetchAll(PDO::FETCH_COLUMN);

        $metaData = [
            'domaines' => $domaines,
            'villes' => $villes,
            'annees' => array_map('strval', $annees),
        ];

        $jsonMetaData = json_encode($metaData, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);

        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        @file_put_contents($cacheFile, $jsonMetaData);

        echo $jsonMetaData;
        exit;

    } catch (Throwable $e) {
        http_response_code(500);
        $debugInfo = $debug ? ['debug' => $e->getMessage()] : [];
        echo json_encode(['error' => 'Erreur lors de la récupération des métadonnées.'] + $debugInfo, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// Liste paginée
$page = max(1, (int) ($_GET['page'] ?? 1));
$page_size = min(50, max(1, (int) ($_GET['page_size'] ?? 12)));

$q = trim((string) ($_GET['q'] ?? ''));
$type = trim((string) ($_GET['type'] ?? ''));
$domaine = trim((string) ($_GET['domaine'] ?? ''));
$ville = trim((string) ($_GET['ville'] ?? ''));
$annee = trim((string) ($_GET['annee'] ?? ''));

$where = [];
$params = [];


$where[] = 'e.is_approved = 1';


$joins = "FROM experiences e 
          LEFT JOIN entreprises ent ON e.entreprise_id = ent.id
          LEFT JOIN domaines d ON e.domaine_id = d.id";

if ($q !== '') {
    // Rend la recherche plus permissive : on sépare par espace ("dupont lille")
    $words = preg_split('/\s+/', $q);
    $q_conditions = [];

    foreach ($words as $i => $word) {
        $paramName = ':q' . $i;
        $q_conditions[] = "(IFNULL(e.entreprise_nom, '') LIKE $paramName 
                  OR IFNULL(e.poste, '') LIKE $paramName 
                  OR IFNULL(e.missions, '') LIKE $paramName 
                  OR IFNULL(e.outils, '') LIKE $paramName 
                  OR IFNULL(e.technos, '') LIKE $paramName
                  OR IFNULL(e.description, '') LIKE $paramName
                  OR IFNULL(e.etudiant_nom, '') LIKE $paramName
                  OR IFNULL(e.etudiant_prenom, '') LIKE $paramName
                  OR IFNULL(d.nom, '') LIKE $paramName)";
        $params[$paramName] = '%' . $word . '%';
    }

    // Tous les mots doivent être présents (AND)
    $where[] = '(' . implode(' AND ', $q_conditions) . ')';
}

// CORRECTION : Ajout des alias 'e.' (et 'd.' pour domaine)
if ($type !== '') {
    $where[] = 'e.type = :type';
    $params[':type'] = $type;
}
if ($domaine !== '') {
    $where[] = 'd.nom = :domaine';
    $params[':domaine'] = $domaine;
} // Filtre sur le nom du domaine
if ($ville !== '') {
    $where[] = 'e.ville = :ville';
    $params[':ville'] = $ville;
}
if ($annee !== '') {
    $where[] = 'e.annee = :annee';
    $params[':annee'] = $annee;
}

// Construction de la clause WHERE
$w = $where ? ('WHERE ' . implode(' AND ', $where)) : '';


try {
    // Total (avec filtres incluant is_approved)
    $sqlCount = "SELECT COUNT(e.id) $joins $w";
    $st = $pdo->prepare($sqlCount);
    $st->execute($params);
    $total = (int) $st->fetchColumn();

    // Items (avec filtres incluant is_approved et pagination)
    $offset = ($page - 1) * $page_size;


    $sql = "SELECT
                e.id, e.entreprise_nom, e.poste, e.type, d.nom AS domaine, e.ville, e.annee,
                e.duree_mois, e.outils, e.description, e.missions,
                e.etudiant_nom, e.etudiant_prenom,
                e.created_at,
                -- Colonnes jointes depuis la table 'entreprises'
                ent.site_web AS entreprise_site, 
                ent.linkedin_url AS entreprise_linkedin,
                ent.contact_phone AS entreprise_phone,
                ent.contact_email AS entreprise_email
            $joins
            $w
            ORDER BY e.annee DESC, e.id DESC
            LIMIT :limit OFFSET :offset";

    $st = $pdo->prepare($sql);

    // Bind des paramètres existants
    foreach ($params as $k => $v)
        $st->bindValue($k, $v, PDO::PARAM_STR);
    // Bind des paramètres de pagination
    $st->bindValue(':limit', $page_size, PDO::PARAM_INT);
    $st->bindValue(':offset', $offset, PDO::PARAM_INT);

    $st->execute();
    $items = $st->fetchAll();

    $pages = max(1, (int) ceil($total / $page_size));

    echo json_encode([
        'page' => $page,
        'page_size' => $page_size,
        'pages' => $pages,
        'total' => $total,
        'items' => $items,
    ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);

} catch (Throwable $e) {

    http_response_code(500);
    $debugInfo = $debug ? ['debug' => $e->getMessage()] : [];
    echo json_encode(['error' => 'Erreur de base de données. Détail: ' . $e->getMessage()] + $debugInfo, JSON_UNESCAPED_UNICODE);
}
?>