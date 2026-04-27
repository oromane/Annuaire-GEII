<?php
// sitemap.php - Plan du site
require dirname(__DIR__) . '/partials/header.php';
$BASE = '/Annuaire';

// Connexion BDD pour les expériences récentes
require_once dirname(__DIR__) . '/api/connexion.php';
$pdo = (isset($pdo) && $pdo instanceof PDO) ? $pdo : (function_exists('get_pdo') ? get_pdo() : null);
?>
<div class="header-spacer"></div>

<main class="main-sitemap">

    <section class="sitemap-hero">
        <h1>Plan du site</h1>
        <p class="subtitle">Vue d'ensemble de toutes les pages de l'Annuaire des Expériences GEII.</p>
    </section>

    <section class="card card--static sitemap-card">
        <div class="card-body">
            <div class="sitemap-grid">

                <!-- Navigation principale -->
                <div class="sitemap-section">
                    <h2>Navigation</h2>
                    <ul>
                        <li><a href="<?= $BASE ?>/index.php">Accueil</a></li>
                        <li><a href="<?= $BASE ?>/pages/annuaire.php">Annuaire des expériences</a></li>
                        <li><a href="<?= $BASE ?>/pages/ajouter.php">Ajouter une expérience</a></li>
                        <li><a href="<?= $BASE ?>/pages/entreprises.php">Entreprises partenaires</a></li>
                        <li><a href="<?= $BASE ?>/pages/contact.php">Contact</a></li>
                    </ul>
                </div>

                <!-- Centre d'aide -->
                <div class="sitemap-section">
                    <h2>Centre d'aide</h2>
                    <ul>
                        <li><a href="<?= $BASE ?>/pages/aide.php">Accueil aide</a></li>
                        <li><a href="<?= $BASE ?>/pages/aide-detail.php?t=cv">Créer un CV efficace</a></li>
                        <li><a href="<?= $BASE ?>/pages/aide-detail.php?t=lettre">Lettre de motivation</a></li>
                        <li><a href="<?= $BASE ?>/pages/aide-detail.php?t=mail">Mail professionnel</a></li>
                        <li><a href="<?= $BASE ?>/pages/aide-detail.php?t=entretien">Réussir l'entretien</a></li>
                    </ul>
                </div>

                <!-- Documents -->
                <div class="sitemap-section">
                    <h2>Documents utiles</h2>
                    <ul>
                        <li><a href="<?= $BASE ?>/pages/documents.php">Tous les documents</a></li>
                        <li><a href="<?= $BASE ?>/docs/CREER_CV_VIDEO.pdf" target="_blank">PDF — CV vidéo</a></li>
                        <li><a href="<?= $BASE ?>/docs/MAIL_PROFESSIONNEL.pdf" target="_blank">PDF — Mail
                                professionnel</a></li>
                        <li><a href="<?= $BASE ?>/docs/DOC1_OBJECTIFS_ALTERNANCE_STAGE.pdf" target="_blank">PDF —
                                Objectifs stage / alternance</a></li>
                    </ul>
                </div>

                <!-- Mentions légales -->
                <div class="sitemap-section">
                    <h2>Légal</h2>
                    <ul>
                        <li><a href="<?= $BASE ?>/pages/terms.php">Mentions légales &amp; conditions d'utilisation</a>
                        </li>
                        <li><a href="<?= $BASE ?>/pages/privacy.php">Politique de confidentialité</a></li>
                        <li><a href="<?= $BASE ?>/pages/sitemap.php">Plan du site</a></li>
                    </ul>
                </div>

            </div>

            <!-- Dernières expériences -->
            <?php if ($pdo): ?>
                <hr style="margin: 2rem 0; border: none; border-top: 1px solid var(--color-border);">
                <div class="sitemap-section">
                    <h2>Dernières expériences ajoutées</h2>
                    <ul>
                        <?php
                        try {
                            $stmt = $pdo->query("SELECT id, poste, entreprise_nom, type FROM experiences WHERE is_approved = 1 ORDER BY created_at DESC LIMIT 15");
                            $exps = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($exps as $exp):
                                ?>
                                <li>
                                    <a href="<?= $BASE ?>/pages/experiences.php?id=<?= (int) $exp['id'] ?>">
                                        <?= htmlspecialchars($exp['poste']) ?> — <?= htmlspecialchars($exp['entreprise_nom']) ?>
                                        <span class="chip--mini"><?= htmlspecialchars($exp['type']) ?></span>
                                    </a>
                                </li>
                                <?php
                            endforeach;
                        } catch (Exception $e) { /* silencieux */
                        }
                        ?>
                    </ul>
                </div>
            <?php endif; ?>

        </div>
    </section>

</main>

<?php include dirname(__DIR__) . '/partials/footer.php'; ?>