<?php
// ajouter.php - Formulaire public MIS À JOUR pour correspondre à la structure de l'API
session_start();

$page_title = 'Ajouter une expérience · Annuaire GEII';
$page_description = 'Partagez votre expérience de stage ou d\'alternance avec les étudiant.e.s GEII. Remplissez le formulaire public pour enrichir l\'annuaire.';

// CSRF token
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'];

// --- LOGIQUE POUR L'ANNÉE UNIVERSITAIRE (Affichage par défaut) ---
$current_year = (int) date('Y');
$academic_start_year = (date('n') >= 7) ? $current_year : $current_year - 1;
$default_annee_display = $academic_start_year . '-' . ($academic_start_year + 1);
?>
<!doctype html>
<html lang="fr" data-theme="<?php echo htmlspecialchars($_COOKIE['theme'] ?? 'light'); ?>">

<head>
    <meta charset="utf-8">
    <title>Ajouter une expérience · Annuaire GEII</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="../assets/css/style.css?v=ajouter-final-v7">

    <style>
        .error-text {
            color: #D9534F;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 4px;
        }

        /* FIX ESSENTIEL pour garantir l'application du display: grid */
        .form-grid {
            display: grid !important;
            gap: 16px 24px;
        }

        .grid-cols-2 {
            grid-template-columns: 1fr 1fr;
        }

        .field input,
        .field select,
        .field textarea {
            width: 100%;
        }

        /* Fix pour que la zone principale prenne 100% de la largeur du conteneur */
        .main-ajouter .ajouter-card,
        .main-ajouter .card-body {
            width: 100%;
        }

        /* --- Validation visuelle en temps réel --- */
        .field input.is-valid,
        .field select.is-valid,
        .field textarea.is-valid {
            border-color: #22C55E;
            background-color: rgba(34, 197, 94, 0.05);
        }

        .field input.is-invalid,
        .field select.is-invalid,
        .field textarea.is-invalid {
            border-color: var(--color-danger, #EF4444);
            background-color: rgba(239, 68, 68, 0.05);
        }

        /* --- Tooltip (info-bulle) --- */
        .field-tooltip {
            position: relative;
            display: inline-block;
            cursor: help;
            margin-left: 4px;
            color: var(--theme-accent);
            font-size: 0.85rem;
            font-weight: 700;
            vertical-align: middle;
        }

        .field-tooltip::before {
            content: attr(data-tip);
            position: absolute;
            bottom: calc(100% + 8px);
            left: 50%;
            transform: translateX(-50%);
            background: #1e293b;
            color: #f8fafc;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 400;
            white-space: nowrap;
            max-width: 260px;
            white-space: normal;
            width: max-content;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
            z-index: 10;
        }

        .field-tooltip:hover::before {
            opacity: 1;
        }
    </style>
</head>

<body>

    <?php include dirname(__DIR__) . '/partials/header.php'; ?>

    <main class="main-ajouter">
        <section class="ajouter-hero">
            <h1>Partage ton expérience 🙌</h1>
            <p class="subtitle">Tu viens de terminer un stage ou une alternance ? Ton retour compte !
                En quelques minutes, aide les futur.e.s étudiant.e.s GEII à découvrir de nouvelles entreprises et de nouveaux
                postes.
                Rappel : une boîte qui prend des stagiaires peut aussi accueillir des alternant.e.s, et inversement !</p>
        </section>

        <section class="card ajouter-card">
            <div class="card-header">
                <h2 class="title">Remplis le formulaire</h2>
                <p class="subtitle">Les champs marqués <span class="req">*</span> sont obligatoires. Plus tu donnes de
                    détails, plus ton témoignage sera utile aux autres ! 💪</p>
            </div>

            <div class="card-body">
                <div id="flash"></div>

                <form id="form" enctype="multipart/form-data" novalidate class="ajouter-form">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
                    <input type="text" name="website" tabindex="-1" autocomplete="off"
                        style="position:absolute;left:-10000px;opacity:0" aria-hidden="true">

                    <fieldset class="form-section">
                        <legend class="form-section-title">Informations Générales</legend>
                        <div class="form-grid grid-cols-2">
                            <div class="field">
                                <label for="etudiant_nom">Nom étudiant.e <span class="req">*</span></label>
                                <input id="etudiant_nom" name="etudiant_nom" required aria-required="true">
                                <div class="error-text" data-for="etudiant_nom"></div>
                            </div>
                            <div class="field">
                                <label for="etudiant_prenom">Prénom étudiant.e <span class="req">*</span></label>
                                <input id="etudiant_prenom" name="etudiant_prenom" required aria-required="true">
                                <div class="error-text" data-for="etudiant_prenom"></div>
                            </div>
                            <div class="field">
                                <label for="entreprise_nom">Entreprise <span class="req">*</span></label>
                                <input id="entreprise_nom" name="entreprise_nom" required aria-required="true"
                                    placeholder="Ex. : Engie, Decathlon…">
                                <div class="error-text" data-for="entreprise_nom"></div>
                            </div>
                            <div class="field">
                                <label for="poste">Poste <span class="req">*</span></label>
                                <input id="poste" name="poste" required aria-required="true"
                                    placeholder="Ex. : Technicien bancs…">
                                <div class="error-text" data-for="poste"></div>
                            </div>
                            <div class="field">
                                <label for="type">Type <span class="req">*</span></label>
                                <select id="type" name="type" aria-required="true">
                                    <option selected>Stage</option>
                                    <option>Alternance</option>
                                </select>
                                <div class="error-text" data-for="type"></div>
                            </div>
                            <div class="field">
                                <label for="domaine">Domaine
                                    <span class="field-tooltip"
                                        data-tip="Exemples : Automatisme, Réseaux, IoT, Énergie, Informatique Industrielle, Électronique...">&#9432;</span>
                                </label>
                                <input id="domaine" name="domaine" placeholder="Automatisme, Énergie…">
                            </div>
                            <div class="field">
                                <label for="ville">Ville</label>
                                <input id="ville" name="ville" placeholder="Lille, Ronchin, Arras...">
                            </div>
                            <div class="field">
                                <label for="annee_univ">Année universitaire <span class="req">*</span>
                                    <span class="field-tooltip"
                                        data-tip="Format attendu : AAAA-AAAA &#xa;Exemple : 2023-2024">&#9432;</span>
                                </label>
                                <input id="annee_univ" name="annee_univ" type="text" required aria-required="true"
                                    placeholder="AAAA-AAAA" value="<?= $default_annee_display ?>">
                                <div class="help">Format : 2024-2025.</div>
                                <div class="error-text" data-for="annee_univ"></div>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="form-section">
                        <legend class="form-section-title">Détails Entreprise (optionnel)</legend>
                        <div class="form-grid grid-cols-2">
                            <div class="field">
                                <label for="entreprise_adresse">Adresse</label>
                                <input id="entreprise_adresse" name="entreprise_adresse">
                            </div>
                            <div class="field">
                                <label for="entreprise_contact">Email</label>
                                <input id="entreprise_contact" name="entreprise_contact"
                                    placeholder="prenom.nom@entreprise.com">
                            </div>
                            <div class="field">
                                <label for="entreprise_phone">Numéro de téléphone</label>
                                <input id="entreprise_phone" name="entreprise_phone" type="tel"
                                    placeholder="02 03 04 05 06">
                            </div>
                            <div class="field">
                                <label for="entreprise_linkedin">LinkedIn Entreprise</label>
                                <input id="entreprise_linkedin" name="entreprise_linkedin" type="url"
                                    placeholder="https://linkedin.com/company/...">
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="form-section">
                        <legend class="form-section-title">Détails Étudiant.e (optionnel)</legend>
                        <div class="form-grid grid-cols-2">
                            <div class="field">
                                <label for="etudiant_email">Email Étudiant.e</label>
                                <input id="etudiant_email" name="etudiant_email" type="email"
                                    placeholder="prenom.nom.etu@univ-lille.fr">
                            </div>
                            <div class="field">
                                <label for="etudiant_linkedin">LinkedIn Étudiant.e</label>
                                <input id="etudiant_linkedin" name="etudiant_linkedin" type="url"
                                    placeholder="https://linkedin.com/in/...">
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="form-section">
                        <legend class="form-section-title">Missions (optionnel)</legend>
                        <div class="form-grid grid-cols-1">
                            <div class="field">
                                <label for="missions">Missions Principales</label>
                                <textarea id="missions" name="missions" rows="6"
                                    placeholder="Tâches principales, contexte, résultats…"></textarea>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="form-section">
                        <legend class="form-section-title">Outils (optionnel)</legend>
                        <div class="form-grid grid-cols-1">
                            <div class="field">
                                <label for="outils">Outils (séparés par virgule)</label>
                                <input id="outils" name="outils" placeholder="Ex: TIA Portal, LabVIEW, Multimètre...">
                            </div>
                        </div>
                    </fieldset>



                    <div class="actions ajouter-form-actions">
                        <a class="btn outline" href="index.php">Annuler</a>
                        <button type="submit" class="btn primary">Enregistrer l'expérience</button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <?php include dirname(__DIR__) . '/partials/footer.php'; ?>

    <script src="../assets/js/ajouter.js?v=final-2"></script>

</body>

</html>