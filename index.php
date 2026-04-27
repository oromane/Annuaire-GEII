<?php
// index.php - Page d’accueil

// Définir le titre de la page AVANT d'inclure le header
$page_title = 'Accueil - Annuaire GEII';
$page_description = 'Bienvenue sur l\'annuaire des stages et alternances GEII. Découvre les expériences partagées par les étudiant.e.s, explore les entreprises et lance ta candidature.';

require __DIR__ . '/partials/header.php'; // Inclut <html>, <head>, <body> et <header>

require __DIR__ . '/api/connexion.php';
$pdo = (isset($pdo) && $pdo instanceof PDO) ? $pdo : (function_exists('get_pdo') ? get_pdo() : null);
if (!$pdo) {
  die('Connexion PDO introuvable (api/connexion.php).');
}

function e($s)
{
  return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

// --- Détermination de l’année universitaire ---
$now = new DateTime();
$year = (int) $now->format('Y');
$month = (int) $now->format('n');
if ($month >= 9) {
  $anneeDebut = $year;
  $anneeFin = $year + 1;
} else {
  $anneeDebut = $year - 1;
  $anneeFin = $year;
}

// ===== Requêtes =====
$latest = [];
try {
  $st = $pdo->prepare("
    SELECT id, type, poste, entreprise_nom AS entreprise
    FROM experiences
    WHERE is_approved = 1 AND deleted_at IS NULL
    ORDER BY id DESC
    LIMIT 6
  ");
  $st->execute();
  $latest = $st->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) { /* silencieux */
}
?>

<main class="home-wrap">

  <section class="card home-hero-card">
    <div class="card-body">
      <h2>Salut et bienvenue sur l'Annuaire GEII ! 👋</h2>
      <p class="muted-text">
        Ce site a été créé <strong>par des étudiant.e.s GEII, pour des étudiant.e.s GEII</strong>.
        On a tous galéré à trouver un stage ou une alternance, alors on a décidé de se filer un coup de main !
        Ici, tu trouveras toutes les expériences de <strong>stages</strong> et d'<strong>alternances</strong>
        partagées par les étudiant.e.s de la formation, promo après promo.
      </p>
      <p class="muted-text" style="margin-top: 0.75rem;">
        💡 <strong>Bon à savoir :</strong> une entreprise qui a accueilli un.e stagiaire peut aussi prendre un.e alternant.e,
        et inversement ! Ne te limite pas au type d'expérience affiché, contacte l'entreprise
        pour lui proposer le format qui te convient.
      </p>
      <p class="muted-text" style="margin-top: 0.75rem;">
        Chaque fiche est un vrai retour d'expérience : l'entreprise, les missions, les outils utilisés, le domaine…
        C'est super pratique pour <strong>cibler tes candidatures</strong>, <strong>préparer tes entretiens</strong>
        et <strong>trouver l'expérience qui te correspond</strong>.
      </p>
      <p class="muted-text" style="margin-top: 0.75rem;">
        <strong>Explore</strong> les entreprises qui nous ont accueillis
        · <strong>Inspire-toi</strong> des parcours de tes camarades
        · <strong>Partage</strong> ta propre expérience pour aider les prochains
        · <strong>Lance-toi</strong> avec confiance dans ta recherche !
      </p>
      <div style="margin-top: 1.5rem;">
        <a class="btn primary" style="font-size: 1.1rem; padding: 0.75rem 1.5rem;" href="pages/annuaire.php">Explorer
          l'Annuaire</a>
      </div>
    </div>
  </section>

  <section class="home-section-latest">
    <div class="home-section-head">
      <h2>Dernières expériences publiées</h2>
      <a class="btn btn--ghost" href="pages/annuaire.php">Tout voir</a>
    </div>

    <?php if (!$latest): ?>
      <p class="help">Aucune expérience approuvée pour l’année universitaire <?= $anneeDebut ?>/<?= $anneeFin ?>.</p>
    <?php else: ?>
      <div class="latest-grid">
        <?php foreach ($latest as $it): ?>
          <article class="xp-card card animate-on-scroll">
            <header class="xp-card__head">
              <div class="xp-card__title">
                <span class="company"><?= e($it['entreprise']) ?></span>
                <span class="job-title"><?= e($it['poste']) ?></span>
              </div>
              <span class="xp-card__badge badge <?= $it['type'] === 'Alternance' ? 'badge-alternance' : 'badge-stage' ?>">
                <?= e($it['type']) ?>
              </span>
            </header>
            <div class="xp-card__body">
              <a class="btn btn--ghost" href="pages/experiences.php?id=<?= (int) $it['id'] ?>">Voir plus</a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>

  <h2 class="section-divider-title">Accès Rapides</h2>

  <section class="quick-grid" aria-label="Actions rapides">
    <article class="card quick-card">
      <div class="card-header quick-header">
        <div class="quick-card__icon" aria-hidden="true">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
            stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8" />
            <line x1="21" y1="21" x2="16.65" y2="16.65" />
          </svg>
        </div>
        <h3>Explorer l'annuaire</h3>
      </div>
      <div class="card-body">
        <p class="help">Parcours les expériences de stages et d'alternances : filtre par domaine, type, ville ou
          entreprise.
          Rappel : une entreprise listée en stage peut aussi accueillir des alternant.e.s, et vice versa !</p>
        <a class="btn" href="pages/annuaire.php">Ouvrir l'annuaire</a>
      </div>
    </article>
    <article class="card quick-card">
      <div class="card-header quick-header">
        <div class="quick-card__icon" aria-hidden="true">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 5v14M5 12l7 7 7-7" />
          </svg>
        </div>
        <h3>Partager ton expérience</h3>
      </div>
      <div class="card-body">
        <p class="help">Tu as terminé un stage ou une alternance ? Fais-en profiter les autres ! Partage ton retour
          d'expérience en quelques minutes, c'est rapide et soumis à validation par l'équipe.</p>
        <a class="btn" href="pages/ajouter.php">Ajouter mon expérience</a>
      </div>
    </article>
    <article class="card quick-card">
      <div class="card-header quick-header">
        <div class="quick-card__icon" aria-hidden="true">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
            stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="7" width="20" height="14" rx="2" />
            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
          </svg>
        </div>
        <h3>Boîte à outils candidature</h3>
      </div>
      <div class="card-body">
        <p class="help">On a rassemblé tous les conseils et documents pour t'aider dans ta candidature : CV, lettre de
          motivation,
          mail pro, entretien… Tout y est, profites-en !</p>
        <a class="btn" href="pages/documents.php">Consulter les ressources</a>
      </div>
    </article>
  </section>

</main>
<?php
// Inclut <footer>, </body> et </html>
include __DIR__ . '/partials/footer.php';
?>