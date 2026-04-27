<?php
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php';
require_login();

// Récupération CSRF Token
$csrf = csrf_token();

// Récupération du thème (Cookie)
$theme = $_COOKIE['theme'] ?? 'light';

// Récupération des expériences supprimées (corbeille)
try {
    $sql = "SELECT id, entreprise_nom, poste, type, etudiant_prenom, etudiant_nom, deleted_at,
                   TIMESTAMPDIFF(MINUTE, deleted_at, NOW()) AS minutes_elapsed
            FROM experiences
            WHERE deleted_at IS NOT NULL
            ORDER BY deleted_at DESC";
    $stmt = $pdo->query($sql);
    $trashedRows = $stmt->fetchAll();
} catch (Throwable $e) {
    log_action('ERROR', 'Erreur BDD sur trash.php: ' . $e->getMessage());
    $trashedRows = [];
    flash_message('Une erreur de base de données est survenue.', 'error');
}
?>
<!doctype html>
<html lang="fr" data-theme="<?= e($theme) ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Corbeille - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=trash-1">
    <style>
        .time-left {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .time-ok {
            background: color-mix(in srgb, var(--color-success), transparent 80%);
            color: var(--color-success);
        }

        .time-warning {
            background: color-mix(in srgb, #FFA500, transparent 80%);
            color: #FFA500;
        }

        .time-danger {
            background: color-mix(in srgb, var(--color-danger), transparent 80%);
            color: var(--color-danger);
        }

        /* Force kebab visibility in trash */
        .kebab-trigger {
            color: var(--color-text-primary) !important;
            font-size: 1.8rem !important;
        }
    </style>
</head>

<body data-theme="<?= e($theme) ?>">

    <main class="admin-main">
        <div class="admin-header">
            <div>
                <h1>📦 Corbeille</h1>
                <p class="subtitle">Expériences supprimées - Restauration possible pendant 1h</p>
            </div>

            <!-- TOGGLE THEME -->
            <div class="theme-toggle">
                <input type="checkbox" id="themeSwitch" class="theme-switch-input" aria-label="Basculer clair/sombre"
                    <?= $theme === 'dark' ? 'checked' : '' ?>>
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

            <a class="btn-sm" href="index.php">← Retour</a>
        </div>

        <?php display_flash_message(); ?>

        <section>
            <h2>Expériences supprimées (
                <?= count($trashedRows) ?>)
            </h2>
            <?php if (empty($trashedRows)): ?>
                <p>La corbeille est vide.</p>
            <?php else: ?>
                <div class="tbl-container">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Entreprise - Poste</th>
                                <th>Type</th>
                                <th>Étudiant</th>
                                <th>Supprimé le</th>
                                <th>Temps restant</th>
                                <th class="col-menu"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($trashedRows as $r):
                                $minutesElapsed = (int) $r['minutes_elapsed'];
                                $minutesLeft = 60 - $minutesElapsed;

                                // Déterminer la classe de couleur
                                if ($minutesLeft > 30) {
                                    $timeClass = 'time-ok';
                                } elseif ($minutesLeft > 10) {
                                    $timeClass = 'time-warning';
                                } else {
                                    $timeClass = 'time-danger';
                                }
                                ?>
                                <tr>
                                    <td>
                                        <?= (int) $r['id'] ?>
                                    </td>
                                    <td>
                                        <?= e($r['entreprise_nom']) ?> -
                                        <?= e($r['poste']) ?>
                                    </td>
                                    <td><span class="chip">
                                            <?= e($r['type']) ?>
                                        </span></td>
                                    <td>
                                        <?= e($r['etudiant_prenom'] . ' ' . $r['etudiant_nom']) ?>
                                    </td>
                                    <td>
                                        <?= date('d/m/Y H:i', strtotime($r['deleted_at'])) ?>
                                    </td>
                                    <td>
                                        <span class="time-left <?= $timeClass ?>">
                                            <?= $minutesLeft ?> min
                                        </span>
                                    </td>
                                    <td class="td-menu">
                                        <div class="kebab-menu">
                                            <button class="kebab-trigger" aria-label="Actions">⋮</button>
                                            <div class="kebab-dropdown">
                                                <form action="restore.php" method="post" style="margin: 0;">
                                                    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                                                    <input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
                                                    <button type="submit" class="kebab-item kebab-approve">♻️ Restaurer</button>
                                                </form>
                                                <form class="js-hard-delete" action="delete_permanent.php" method="post"
                                                    style="margin: 0;">
                                                    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                                                    <input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
                                                    <button type="submit" class="kebab-item kebab-delete">🗑️ Supprimer
                                                        définitivement</button>
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
    </main>

    <script>
        // Kebab menu toggle
        document.addEventListener('click', function (e) {
            // Check if clicking a submit button inside the menu
            if (e.target.closest('button[type="submit"]')) {
                return;
            }
            if (e.target.closest('.kebab-trigger')) {
                e.preventDefault();
                e.stopPropagation();
                const dropdown = e.target.closest('.kebab-menu').querySelector('.kebab-dropdown');

                document.querySelectorAll('.kebab-dropdown.is-open').forEach(dd => {
                    if (dd !== dropdown) dd.classList.remove('is-open');
                });

                dropdown.classList.toggle('is-open');
            } else if (!e.target.closest('.kebab-dropdown')) {
                document.querySelectorAll('.kebab-dropdown.is-open').forEach(dd => {
                    dd.classList.remove('is-open');
                });
            }
        });

        // Confirmation de suppression DÉFINITIVE (Double clic)
        document.querySelectorAll('.js-hard-delete').forEach(form => {
            form.addEventListener('submit', function (e) {
                const btn = form.querySelector('button[type="submit"]');

                if (!btn.classList.contains('is-confirming')) {
                    e.preventDefault(); // Arrête la soumission

                    if (!btn.dataset.originalHtml) {
                        btn.dataset.originalHtml = btn.innerHTML;
                    }

                    btn.classList.add('is-confirming');
                    btn.innerHTML = '<span style="font-weight:700;">⚠️ Sûr ? (Définitif)</span>';
                    btn.style.backgroundColor = 'color-mix(in srgb, var(--color-danger) 15%, transparent)';
                    btn.style.paddingLeft = '1rem';

                    setTimeout(() => {
                        if (btn.classList.contains('is-confirming')) {
                            btn.classList.remove('is-confirming');
                            btn.innerHTML = btn.dataset.originalHtml;
                            btn.style.backgroundColor = '';
                            btn.style.paddingLeft = '';
                        }
                    }, 3000);
                }
            });
        });
    </script>
    <script src="../assets/js/header.js"></script>
</body>

</html>