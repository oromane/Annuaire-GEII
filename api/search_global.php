<?php
// api/search_global.php
// Recherche globale : Pages, Documents, Entreprises, Expériences

header('Content-Type: application/json; charset=utf-8');

require __DIR__ . '/connexion.php';

$q = $_GET['q'] ?? '';
$q = trim($q);

if (mb_strlen($q) < 2) {
    echo json_encode([]);
    exit;
}

$results = [];

// 1. PAGES STATIQUES & ACTIONS
// Mots-clés simples pour rediriger vers des pages
$staticPages = [
    ['keys' => ['contact', 'mail', 'message', 'ecrire'], 'label' => 'Contact', 'url' => 'pages/contact.php', 'icon' => '', 'type' => 'Page'],
    ['keys' => ['ajouter', 'partager', 'nouveau', 'creer experience'], 'label' => 'Ajouter une expérience', 'url' => 'pages/ajouter.php', 'icon' => '', 'type' => 'Action'],
    ['keys' => ['annuaire', 'liste', 'recherche', 'tout voir'], 'label' => 'Consulter l\'Annuaire', 'url' => 'pages/annuaire.php', 'icon' => '', 'type' => 'Page'],
    ['keys' => ['accueil', 'home', 'retour'], 'label' => 'Accueil', 'url' => 'index.php', 'icon' => '', 'type' => 'Page'],
    ['keys' => ['documents', 'aide', 'pdf', 'conseil'], 'label' => 'Documents & Conseils', 'url' => 'pages/documents.php', 'icon' => '', 'type' => 'Page'],
    ['keys' => ['login', 'admin', 'connexion'], 'label' => 'Connexion Admin', 'url' => 'admin/login.php', 'icon' => '', 'type' => 'Admin'],
];

foreach ($staticPages as $page) {
    foreach ($page['keys'] as $k) {
        if (stripos($k, $q) !== false || stripos($q, $k) !== false) {
            $results[] = [
                'type' => 'Page', // Catégorie pour l'affichage
                'label' => $page['label'],
                'sub' => $page['type'], // Sous-info
                'url' => '/Annuaire/' . $page['url'], // Chemin relatif adaptatif si besoin
                'icon' => $page['icon']
            ];
            break; // On ajoute la page une seule fois max
        }
    }
}

// 2. DOCUMENTS & AIDE
// Index "en dur" des sujets traités dans documents-detail.php
$helpTopics = [
    ['keys' => ['cv', 'curriculum'], 'label' => 'Conseils pour le CV', 't' => 'cv'],
    ['keys' => ['lettre', 'motivation'], 'label' => 'Lettre de Motivation', 't' => 'lettre'],
    ['keys' => ['mail', 'email', 'courriel'], 'label' => 'Mail de candidature', 't' => 'mail'],
    ['keys' => ['entretien', 'embauche', 'oral'], 'label' => 'Réussir l\'entretien', 't' => 'entretien'],
    ['keys' => ['objectif', 'jalon'], 'label' => 'Objectifs de stage/alt', 't' => 'objectifs'],
];

foreach ($helpTopics as $topic) {
    foreach ($topic['keys'] as $k) {
        if (stripos($topic['label'], $q) !== false || stripos($k, $q) !== false || stripos($q, $k) !== false) {
            $results[] = [
                'type' => 'Conseils',
                'label' => $topic['label'],
                'sub' => 'Centre de documents',
                'url' => '/Annuaire/pages/documents-detail.php?t=' . $topic['t'],
                'icon' => ''
            ];
            break;
        }
    }
}

// 3. BASE DE DONNÉES (Entreprises & Expériences — Fuzzy)
try {
    $pdo = get_pdo();

    // Fonction helper pour la pertinence (0 = parfait, + = moins bon)
    // Retourne -1 si hors tolérance
    function getMatchScore($query, $text)
    {
        $q = mb_strtolower(trim($query));
        $t = mb_strtolower(trim($text));

        // 1. Exact match ou Substring (Priorité absolu)
        if (strpos($t, $q) !== false)
            return 0;

        // 2. Levenshtein (Tolérance fautes)
        // On accepte 2 erreurs max pour les mots > 3 lettres
        $limit = (mb_strlen($q) > 3) ? 2 : 1;
        $dist = levenshtein($q, $t);

        if ($dist <= $limit)
            return $dist;

        return -1;
    }

    // A. Recherche Entreprises (SQL LIKE reste performant pour ça)
    $stmtEnt = $pdo->prepare("SELECT nom, ville FROM entreprises WHERE nom LIKE :q LIMIT 3");
    $stmtEnt->execute([':q' => "%$q%"]);
    while ($row = $stmtEnt->fetch()) {
        $results[] = [
            'type' => 'Entreprise',
            'label' => $row['nom'],
            'sub' => $row['ville'] ?: 'Ville inconnue',
            'url' => '/Annuaire/pages/annuaire.php?q=' . urlencode($row['nom']),
            'icon' => ''
        ];
    }

    // B. Recherche Expériences (Postes, Missions, Outils)
    $stmtExp = $pdo->prepare("
        SELECT DISTINCT poste, missions, outils 
        FROM experiences 
        WHERE (poste LIKE :q OR missions LIKE :q OR outils LIKE :q) 
          AND is_approved = 1 
        LIMIT 3
    ");
    $stmtExp->execute([':q' => "%$q%"]);
    while ($row = $stmtExp->fetch()) {
        $sub = 'Rechercher ce poste';
        // Détection du contexte (Pourquoi ce résultat sort ?)
        if (stripos($row['outils'], $q) !== false) {
            $sub = 'Outils : ' . $q . '...';
        } elseif (stripos($row['missions'], $q) !== false) {
            $sub = 'Missions : ' . $q . '...';
        }

        $results[] = [
            'type' => 'Annuaire',
            'label' => $row['poste'],
            'sub' => $sub,
            'url' => '/Annuaire/pages/annuaire.php?q=' . urlencode($row['poste']),
            'icon' => ''
        ];
    }

    // C. Recherche FUZZY (Étudiants, Domaines, Villes)
    // Pre-filter with SQL LIKE to avoid loading entire tables
    $likeQ = '%' . mb_substr($q, 0, 2) . '%';

    // -- Étudiants --
    $stmtEtu = $pdo->prepare("SELECT DISTINCT etudiant_nom, etudiant_prenom FROM experiences WHERE is_approved = 1 AND (etudiant_nom LIKE :q OR etudiant_prenom LIKE :q) LIMIT 20");
    $stmtEtu->execute([':q' => $likeQ]);
    $allEtudiants = $stmtEtu->fetchAll();
    $matchesEtu = [];
    foreach ($allEtudiants as $row) {
        $p = $row['etudiant_prenom'];
        $n = $row['etudiant_nom'];
        $full1 = $p . ' ' . $n;
        $full2 = $n . ' ' . $p;

        $score = getMatchScore($q, $full1);
        if ($score == -1)
            $score = getMatchScore($q, $full2);
        if ($score == -1)
            $score = getMatchScore($q, $n);
        if ($score == -1)
            $score = getMatchScore($q, $p);

        if ($score !== -1) {
            $matchesEtu[] = ['data' => $full1, 'simple' => $p, 'score' => $score];
        }
    }
    usort($matchesEtu, fn($a, $b) => $a['score'] <=> $b['score']);
    foreach (array_slice($matchesEtu, 0, 3) as $m) {
        $results[] = [
            'type' => 'Étudiant',
            'label' => $m['data'],
            'sub' => 'Voir les expériences',
            'url' => '/Annuaire/pages/annuaire.php?q=' . urlencode($m['simple']),
            'icon' => '',
            'score' => $m['score']
        ];
    }

    // -- Domaines --
    $stmtDom = $pdo->prepare("SELECT DISTINCT domaine FROM experiences WHERE is_approved = 1 AND domaine LIKE :q LIMIT 10");
    $stmtDom->execute([':q' => $likeQ]);
    $allDomaines = $stmtDom->fetchAll(PDO::FETCH_COLUMN);
    $matchesDom = [];
    foreach ($allDomaines as $dom) {
        $score = getMatchScore($q, $dom);
        if ($score !== -1) {
            $matchesDom[] = ['data' => $dom, 'score' => $score];
        }
    }
    usort($matchesDom, fn($a, $b) => $a['score'] <=> $b['score']);
    foreach (array_slice($matchesDom, 0, 2) as $m) {
        $results[] = [
            'type' => 'Domaine',
            'label' => $m['data'],
            'sub' => 'Filtrer par domaine',
            'url' => '/Annuaire/pages/annuaire.php?domaine=' . urlencode($m['data']),
            'icon' => '',
            'score' => $m['score']
        ];
    }

    // -- Villes --
    $stmtVille = $pdo->prepare("SELECT DISTINCT ville FROM experiences WHERE is_approved = 1 AND ville LIKE :q LIMIT 10");
    $stmtVille->execute([':q' => $likeQ]);
    $allVilles = $stmtVille->fetchAll(PDO::FETCH_COLUMN);
    $matchesVille = [];
    foreach ($allVilles as $ville) {
        if (!$ville)
            continue;
        $score = getMatchScore($q, $ville);
        if ($score !== -1) {
            $matchesVille[] = ['data' => $ville, 'score' => $score];
        }
    }
    usort($matchesVille, fn($a, $b) => $a['score'] <=> $b['score']);
    foreach (array_slice($matchesVille, 0, 2) as $m) {
        $results[] = [
            'type' => 'Ville',
            'label' => $m['data'],
            'sub' => 'Voir les expériences à ' . $m['data'],
            'url' => '/Annuaire/pages/annuaire.php?ville=' . urlencode($m['data']),
            'icon' => '',
            'score' => $m['score'] + 1
        ];
    }

} catch (Exception $e) {
    // Continue sans DB
}

// 4. TRI FINAL INTELLIGENT
// On trie tous les résultats (Pages, Ent, Etu...) par score (si dispo) puis par Type
usort($results, function ($a, $b) {
    $scoreA = $a['score'] ?? 10; // Valeur par défaut si pas de score calculé
    $scoreB = $b['score'] ?? 10;

    // 1. D'abord le score (plus petit = meilleur match)
    if ($scoreA !== $scoreB)
        return $scoreA <=> $scoreB;

    // 2. Ensuite priorité des Types
    $priorities = ['Page' => 1, 'Étudiant' => 2, 'Domaine' => 3, 'Conseils' => 4, 'Entreprise' => 5, 'Ville' => 6, 'Annuaire' => 7];
    $pA = $priorities[$a['type']] ?? 99;
    $pB = $priorities[$b['type']] ?? 99;

    return $pA <=> $pB;
});

// Format de sortie JSON
echo json_encode(array_values($results));
