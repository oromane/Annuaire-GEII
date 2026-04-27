<?php
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php'; // D'abord le bootstrap qui gère la session
require_login();

// Auto-cleanup: Supprime les expériences supprimées depuis plus d'1h
require __DIR__ . '/../api/cleanup.php';
cleanup_deleted_experiences($pdo);

// Récupération CSRF Token
$csrf = csrf_token();

// Récupération du paramètre de recherche
$q = trim($_GET['q'] ?? '');

// --- Récupération des expériences EN ATTENTE ---
try {
    $sqlPending = "SELECT id, entreprise_nom, poste, type, etudiant_prenom, etudiant_nom, created_at, email_verified_at
                   FROM experiences
                   WHERE is_approved = 0 AND deleted_at IS NULL
                   ORDER BY created_at ASC";
    $stmtPending = $pdo->query($sqlPending);
    $pendingRows = $stmtPending->fetchAll();

    // --- Récupération des expériences APPROUVÉES ---
    $params = [];
    $whereConditions = ['is_approved = 1', 'deleted_at IS NULL'];
    if ($q !== '') {
        $whereConditions[] = "(poste LIKE :q OR entreprise_nom LIKE :q OR etudiant_nom LIKE :q OR etudiant_prenom LIKE :q)";
        $params[':q'] = "%$q%";
    }
    $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);

    $sqlApproved = "SELECT id, entreprise_nom, poste, type, etudiant_prenom, etudiant_nom, ville, annee, email_verified_at
                   FROM experiences {$whereClause}
                   ORDER BY id DESC LIMIT 200";
    $stApproved = $pdo->prepare($sqlApproved);
    $stApproved->execute($params);
    $approvedRows = $stApproved->fetchAll();
} catch (Throwable $e) {
    log_action('ERROR', 'Erreur BDD sur admin/index.php: ' . $e->getMessage());
    $pendingRows = [];
    $approvedRows = [];
    flash_message('Une erreur de base de données est survenue.', 'error');
}
?>
<?php
// Récupération du thème (Cookie)
$theme = $_COOKIE['theme'] ?? 'light';
?>
<!doctype html>
<html lang="fr" data-theme="<?= e($theme) ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Expériences</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=admin-4">
</head>

<body>

    <main class="admin-main">
        <div class="admin-header">
            <div>
                <h1>Administration</h1>
                <p class="subtitle">Gestion des expériences · Connecté en tant que
                    <strong><?= e($_SESSION['admin_username']); ?></strong>
                </p>
            </div>

            <div class="admin-header-actions">
                <!-- TOGGLE THEME -->
                <div class="theme-toggle">
                    <input type="checkbox" id="themeSwitch" class="theme-switch-input"
                        aria-label="Basculer clair/sombre" <?= $theme === 'dark' ? 'checked' : '' ?>>
                    <label for="themeSwitch" class="toggle-track new-toggle-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" fill="currentColor"
                            stroke-linecap="round" viewBox="0 0 32 32" class="toggle-svg">
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

                <a class="btn-sm btn-ghost" href="trash.php">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="21 8 21 21 3 21 3 8"></polyline>
                        <rect x="1" y="3" width="22" height="5"></rect>
                        <line x1="10" y1="12" x2="14" y2="12"></line>
                    </svg>
                    Corbeille
                </a>
                <a class="btn-sm btn-danger-outline" href="logout.php">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    Déconnexion
                </a>
            </div>
        </div>

        <?php display_flash_message(); ?>

        <!-- STATS DASHBOARD -->
        <?php
        // Calcul des stats
        try {
            $stats = [
                'total' => $pdo->query("SELECT COUNT(*) FROM experiences")->fetchColumn(),
                'pending' => count($pendingRows),
                'companies' => $pdo->query("SELECT COUNT(*) FROM entreprises")->fetchColumn(),
                'visits_today' => $pdo->query("SELECT COUNT(DISTINCT ip_address) FROM stats_visits WHERE visit_date = CURDATE()")->fetchColumn(),
                // Total des "sessions" (combinaisons uniques IP + Date)
                'visits_total' => $pdo->query("SELECT COUNT(*) FROM (SELECT DISTINCT ip_address, visit_date FROM stats_visits) AS t")->fetchColumn()
            ];
        } catch (Throwable $e) {
            $stats = ['total' => 0, 'pending' => 0, 'companies' => 0, 'visits_today' => 0, 'visits_total' => 0];
        }
        ?>
        <section class="admin-stats">
            <div class="stat-card">
                <div class="stat-icon stat-icon--accent">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                    </svg>
                </div>
                <div class="stat-value"><?= $stats['total'] ?></div>
                <div class="stat-label">Expériences</div>
            </div>
            <div class="stat-card stat-card--danger">
                <div class="stat-icon stat-icon--danger">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </div>
                <div class="stat-value"><?= $stats['pending'] ?></div>
                <div class="stat-label">En attente</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon stat-icon--purple">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                </div>
                <div class="stat-value"><?= $stats['companies'] ?></div>
                <div class="stat-label">Entreprises</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon stat-icon--green">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <div class="stat-value"><?= $stats['visits_today'] ?></div>
                <div class="stat-label">Visiteurs (Auj.)</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon stat-icon--amber">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                    </svg>
                </div>
                <div class="stat-value"><?= $stats['visits_total'] ?></div>
                <div class="stat-label">Visites (Total)</div>
            </div>
        </section>

        <section class="pending-section">
            <h2>⏳ Expériences en attente d'approbation <span class="admin-count"><?= count($pendingRows) ?></span></h2>
            <?php if (empty($pendingRows)): ?>
                <p>Aucune expérience en attente.</p>
            <?php else: ?>
                <div class="tbl-container">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Entreprise - Poste</th>
                                <th>Type</th>
                                <th>Étudiant (Email)</th>
                                <th>Soumis le</th>
                                <th class="col-menu"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingRows as $r): ?>
                                <tr data-id="<?= (int) $r['id'] ?>">
                                    <td><?= (int) $r['id'] ?></td>
                                    <td><?= e($r['entreprise_nom']) ?> - <?= e($r['poste']) ?></td>
                                    <td><span class="chip"><?= e($r['type']) ?></span></td>
                                    <td>
                                        <?= e($r['etudiant_prenom'] . ' ' . $r['etudiant_nom']) ?>
                                        <?php if ($r['email_verified_at']): ?>
                                            <span class="badge-verified" title="Email vérifié">✔</span>
                                        <?php else: ?>
                                            <span class="badge-unverified" title="Email non vérifié">⨯</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($r['created_at'])) ?></td>
                                    <td class="td-menu">
                                        <div class="kebab-menu">
                                            <button type="button" class="kebab-trigger" aria-label="Actions">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <circle cx="12" cy="12" r="1"></circle>
                                                    <circle cx="12" cy="5" r="1"></circle>
                                                    <circle cx="12" cy="19" r="1"></circle>
                                                </svg>
                                            </button>
                                            <div class="kebab-dropdown">
                                                <form action="approve.php" method="post" style="margin: 0;">
                                                    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                                                    <input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
                                                    <button type="submit" class="kebab-item kebab-approve">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <polyline points="20 6 9 17 4 12"></polyline>
                                                        </svg>
                                                        Approuver
                                                    </button>
                                                </form>
                                                <a href="edit.php?id=<?= (int) $r['id'] ?>" class="kebab-item">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                                        </path>
                                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                                        </path>
                                                    </svg>
                                                    Modifier
                                                </a>
                                                <form class="js-delete-form" action="delete.php" method="post"
                                                    style="margin: 0;">
                                                    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                                                    <input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
                                                    <button type="submit" class="kebab-item kebab-delete">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                            <path
                                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                            </path>
                                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                                        </svg>
                                                        Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>

        <hr style="border: none; border-top: 1px solid var(--card-border); margin: 40px 0;">

        <section>
            <h2>✅ Expériences Approuvées</h2>
            <div class="toolbar">
                <form method="get">
                    <input name="q" placeholder="Rechercher dans les approuvées..." value="<?= e($q) ?>" type="search">
                    <button class="btn-sm btn-ghost" type="submit">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        Rechercher
                    </button>
                    <?php if ($q !== ''): ?>
                        <a href="index.php" class="btn-sm btn-ghost">Voir tout</a>
                    <?php endif; ?>
                </form>
                <a class="btn-sm btn-primary" href="edit.php">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Ajouter
                </a>
            </div>

            <div class="tbl-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Entreprise - Poste</th>
                            <th>Type</th>
                            <th>Étudiant (Email)</th>
                            <th>Ville</th>
                            <th>Année</th>
                            <th class="col-menu"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($approvedRows as $r): ?>
                            <tr data-id="<?= (int) $r['id'] ?>">
                                <td><?= (int) $r['id'] ?></td>
                                <td><?= e($r['entreprise_nom']) ?> - <?= e($r['poste']) ?></td>
                                <td><span class="chip"><?= e($r['type']) ?></span></td>
                                <td>
                                    <?= e($r['etudiant_prenom'] . ' ' . $r['etudiant_nom']) ?>
                                    <?php if ($r['email_verified_at']): ?>
                                        <span class="badge-verified" title="Email vérifié">✔</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= e($r['ville'] ?: '-') ?></td>
                                <td><?= e($r['annee']) ?></td>
                                <td class="td-menu">
                                    <div class="kebab-menu">
                                        <button type="button" class="kebab-trigger" aria-label="Actions">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="1"></circle>
                                                <circle cx="12" cy="5" r="1"></circle>
                                                <circle cx="12" cy="19" r="1"></circle>
                                            </svg>
                                        </button>
                                        <div class="kebab-dropdown">
                                            <a href="edit.php?id=<?= (int) $r['id'] ?>" class="kebab-item">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                                    </path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                                    </path>
                                                </svg>
                                                Modifier
                                            </a>
                                            <form class="js-delete-form" action="delete.php" method="post"
                                                style="margin: 0;">
                                                <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                                                <input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
                                                <button type="submit" class="kebab-item kebab-delete">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path
                                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                        </path>
                                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                                    </svg>
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach;
                        if (empty($approvedRows)): ?>
                            <tr>
                                <td colspan="7">
                                    <?= $q !== '' ? 'Aucun résultat pour votre recherche.' : 'Aucune expérience approuvée.' ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script>
        // === KEBAB MENU TOGGLE ===
        document.addEventListener('click', function (e) {
            // Allow form submission buttons (like delete and approve) to work normally
            if (e.target.closest('button[type="submit"]')) {
                return;
            }

            // Toggle dropdown on kebab button click
            if (e.target.closest('.kebab-trigger')) {
                e.stopPropagation();
                e.preventDefault(); // Prevent accidental form submissions if trigger is ever a submit button
                const dropdown = e.target.closest('.kebab-menu').querySelector('.kebab-dropdown');

                // Close all other dropdowns
                document.querySelectorAll('.kebab-dropdown.is-open').forEach(dd => {
                    if (dd !== dropdown) dd.classList.remove('is-open');
                });

                // Toggle current dropdown
                dropdown.classList.toggle('is-open');
            }
            // Close all dropdowns when clicking outside
            else if (!e.target.closest('.kebab-dropdown')) {
                document.querySelectorAll('.kebab-dropdown.is-open').forEach(dd => {
                    dd.classList.remove('is-open');
                });
            }
        });

        // === DELETE CONFIRMATION (Double clic pour confirmer) ===
        document.querySelectorAll('.js-delete-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                const btn = form.querySelector('button[type="submit"]');

                // Si on n'est pas encore en mode confirmation
                if (!btn.classList.contains('is-confirming')) {
                    e.preventDefault(); // Arrête la soumission

                    // Sauvegarde du style d'origine
                    if (!btn.dataset.originalHtml) {
                        btn.dataset.originalHtml = btn.innerHTML;
                    }

                    // Passage en mode "Confirmer"
                    btn.classList.add('is-confirming');
                    btn.innerHTML = '<span style="font-weight:700;">⚠️ Confirmer ?</span>';
                    btn.style.backgroundColor = 'color-mix(in srgb, var(--color-danger) 15%, transparent)';
                    btn.style.paddingLeft = '1rem';

                    // Annuler après 3 secondes si pas de clic
                    setTimeout(() => {
                        if (btn.classList.contains('is-confirming')) {
                            btn.classList.remove('is-confirming');
                            btn.innerHTML = btn.dataset.originalHtml;
                            btn.style.backgroundColor = '';
                            btn.style.paddingLeft = '';
                        }
                    }, 3000);
                }
                // Si on est déjà en mode confirmation, la soumission se fait normalement
            });
        });
    </script>
    <script src="../assets/js/header.js"></script>
</body>

</html>