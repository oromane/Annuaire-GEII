<?php

$BASE = '/Annuaire';

$theme = isset($_COOKIE['theme']) ? htmlspecialchars($_COOKIE['theme']) : 'light';

// --- VISITOR TRACKING ---
// S'assurer qu'on a une connexion DB (car header.php est souvent inclus AVANT connexion.php)
if (!isset($pdo) || !$pdo instanceof PDO) {
  // Tente d'inclure la connexion si elle n'est pas là
  $connFiles = [
    __DIR__ . '/../api/connexion.php', // Chemin relatif standard
    __DIR__ . '/api/connexion.php'     // Cas où header est inclus depuis la racine
  ];
  foreach ($connFiles as $f) {
    if (file_exists($f)) {
      require_once $f;
      if (function_exists('get_pdo')) {
        $pdo = get_pdo();
      }
      break;
    }
  }
}

if (isset($pdo) && $pdo instanceof PDO) {
  try {
    // Enregistrer la visite (hash IP avec salt journalier pour le RGPD)
    $rawIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    // Salt qui change tous les jours pour empêcher de traquer un user sur plusieurs jours
    $dailySalt = date('Y-m-d');
    $hashedIp = hash('sha256', $rawIp . $dailySalt);

    // Insérer (on pourrait vérifier si la visite existe déjà via UNIQUE index, mais ce n'est pas critique ici)
    $sqlTrack = "INSERT INTO stats_visits (page_url, visit_date, ip_address, user_agent) VALUES (:url, CURDATE(), :ip, :ua)";
    $stmtTrack = $pdo->prepare($sqlTrack);
    $stmtTrack->execute([
      ':url' => $_SERVER['REQUEST_URI'] ?? 'unknown',
      ':ip' => $hashedIp,
      ':ua' => $_SERVER['HTTP_USER_AGENT'] ?? ''
    ]);
  } catch (Throwable $e) {
    // Silencieux si échec tracking (ne pas bloquer le site)
  }
}
?>
<!doctype html>
<html lang="fr" data-theme="<?= $theme ?>">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?= $page_title ?? 'Annuaire des expériences GEII' ?></title>
  <meta name="description"
    content="<?= $page_description ?? 'L\'annuaire créé par des étudiant.e.s GEII, pour des étudiant.e.s GEII. Retrouve les stages et alternances de tes camarades, inspire-toi et lance ta candidature !' ?>">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="http://<?= $_SERVER['HTTP_HOST'] ?><?= $_SERVER['REQUEST_URI'] ?>">
  <meta property="og:title" content="<?= $page_title ?? 'Annuaire des expériences GEII' ?>">
  <meta property="og:description"
    content="<?= $page_description ?? 'L\'annuaire créé par des étudiant.e.s GEII, pour des étudiant.e.s GEII. Stages, alternances, conseils et retours d\'expérience.' ?>">
  <meta property="og:image" content="http://<?= $_SERVER['HTTP_HOST'] ?><?= $BASE ?>/assets/images/og-image.jpg">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:url" content="http://<?= $_SERVER['HTTP_HOST'] ?><?= $_SERVER['REQUEST_URI'] ?>">
  <meta property="twitter:title" content="<?= $page_title ?? 'Annuaire des expériences GEII' ?>">
  <meta property="twitter:description"
    content="L'annuaire des stages et alternances GEII, par des étudiant.e.s pour des étudiant.e.s.">
  <meta property="twitter:image" content="http://<?= $_SERVER['HTTP_HOST'] ?><?= $BASE ?>/assets/images/og-image.jpg">

  <link rel="apple-touch-icon" sizes="180x180" href="<?= $BASE ?>/assets/images/apple-touch-icon.png">
  <link rel="stylesheet" href="<?= $BASE ?>/assets/css/style.css?v=final-18">
  <link rel="icon" href="<?= $BASE ?>/assets/favicon.ico">
</head>

<body>

  <header class="site-header">
    <div class="hdr-inner">
      <button id="hdrBurger" class="hdr-burger" aria-expanded="false" aria-controls="hdrMobile"
        aria-label="Ouvrir le menu">
        <span></span><span></span><span></span>
      </button>

      <div class="hdr-brand">
        <a href="<?= $BASE ?>/index.php" style="text-decoration: none;">
          <div class="title">Annuaire des expériences GEII</div>
          <div class="subtitle">Stages & Alternances - par des étudiants, pour des étudiants.es</div>
        </a>
      </div>

      <nav class="hdr-nav">
        <a href="<?= $BASE ?>/index.php" class="nav-link">Accueil</a>
        <a href="<?= $BASE ?>/pages/annuaire.php" class="nav-link">Annuaire</a>
        <a href="<?= $BASE ?>/pages/documents.php" class="nav-link">Documents</a>
        <a href="<?= $BASE ?>/pages/contact.php" class="nav-link">Contact</a>
      </nav>

      <div class="hdr-actions">
        <form id="hdrSearchForm" class="hdr-search" action="<?= $BASE ?>/pages/annuaire.php" method="get">

          <input id="hdrSearchInput" type="search" name="q" placeholder="Rechercher..." aria-label="Rechercher"
            autocomplete="off">

          <div id="hdrSearchResults" class="search-results"></div>

        </form>

        <div class="hdr-logos-group">
          <a href="https://iut.univ-lille.fr/" target="_blank" title="Site de l'IUT">
            <img src="<?= $BASE ?>/assets/images/logoIUTGEIISombre.svg" alt="IUT Univ Lille GEII"
              class="hdr-logo logo-light-only">
            <img src="<?= $BASE ?>/assets/images/LogoIUTGEIIclair.svg" alt="IUT Univ Lille GEII" class="hdr-logo
            logo-dark-only">
          </a>
        </div>

        <div class="theme-toggle">
          <input type="checkbox" id="themeSwitch" class="theme-switch-input" aria-label="Basculer clair/sombre" <?php if ($theme === 'dark')
            echo 'checked'; ?>>

          <label for="themeSwitch" class="toggle-track new-toggle-btn">
            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" fill="currentColor" stroke-linecap="round"
              viewBox="0 0 32 32" class="toggle-svg">
              <clipPath id="skiper-btn-3">
                <path class="toggle-clip-path" d="M0-11h25a1 1 0 0017 13v30H0Z" />
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
      </div>
    </div>

    <aside id="hdrMobile" class="hdr-mobile" aria-hidden="true">
      <form class="mobile-search" action="<?= $BASE ?>/pages/annuaire.php" method="get">
        <input type="search" name="q" placeholder="Rechercher une expérience..." aria-label="Rechercher">
      </form>
      <a class="m-link" href="<?= $BASE ?>/index.php" style="--i: 1">Accueil</a>
      <a class="m-link" href="<?= $BASE ?>/pages/annuaire.php" style="--i: 2">Annuaire</a>
      <a class="m-link" href="<?= $BASE ?>/pages/documents.php" style="--i: 3">Documents</a>
      <a class="m-link" href="<?= $BASE ?>/pages/contact.php" style="--i: 4">Contact</a>
    </aside>
  </header>

  <script>window.APP_BASE = '<?= $BASE ?>';</script>
  <script defer src="<?= $BASE ?>/assets/js/header.js?v=final-4"></script>