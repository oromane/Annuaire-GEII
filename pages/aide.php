<?php
// aide.php - Centre d’aide complet 
$page_title = 'Aide - Annuaire GEII';
$page_description = 'Retrouve tous nos conseils pour rédiger ton CV, préparer tes entretiens et décrocher ton stage ou alternance en GEII.';
require dirname(__DIR__) . '/partials/header.php';
?>
<div class="header-spacer"></div>

<main class="main-aide">
    <section class="help-hero">
        <h1>Centre d’aide</h1>
        <p>Besoin d'un coup de pouce pour décrocher ton stage ou ton alternance ? Tu trouveras ici des conseils
            pratiques,
            des modèles et des ressources pour chaque étape de ta candidature : rédiger un CV percutant, écrire une
            lettre de motivation convaincante,
            envoyer un mail professionnel ou encore réussir ton entretien. Tout est là pour t'aider à mettre toutes les
            chances de ton côté ! 💪
        </p>

        <nav class="help-chips" aria-label="Raccourcis">
            <a class="chip" href="#preparer"><span class="ico">🧭</span> Préparer sa candidature</a>
            <a class="chip" href="#entretien"><span class="ico">🎤</span> Réussir l’entretien</a>
            <a class="chip" href="#docs"><span class="ico">📄</span> Documents utiles</a>
            <a class="chip" href="#liens"><span class="ico">🔗</span> Liens externes</a>
        </nav>
    </section>

    <div class="aide-grid">
        <article id="preparer" class="card aid-card">
            <div class="card-header">
                <h2>Créer un CV efficace</h2>
            </div>
            <a class="btn btn--ghost aid-action" href="aide-detail.php?t=cv">Voir plus</a>
            <div class="card-body">
                <p><em>Lisible, ciblé, professionnel</em></p>
                <ul>
                    <li>1 page claire, avec un <strong>objectif</strong> accrocheur lié au poste.</li>
                    <li>Mets en avant <strong>projets</strong>, <strong>compétences</strong> (automatisme, IoT,
                        énergie…), <strong>outils</strong> et <strong>réalisations</strong>.</li>
                    <li>Hiérarchise : expériences récentes en premier, dates alignées.</li>
                    <li>Relis : orthographe, mail pro, liens (GitHub/LinkedIn) valides.</li>
                </ul>
                <p class="help">
                    Ressources :
                    <a href="docs/sitographie_cvlm.pdf" target="_blank" rel="noopener">Sitographie CV & LM</a> ·
                    <a href="docs/CREER_CV_VIDEO.pdf" target="_blank" rel="noopener">Créer un CV vidéo</a>
                </p>
            </div>
        </article>

        <article class="card aid-card">
            <div class="card-header">
                <h2>Lettre de motivation</h2>
            </div>
            <a class="btn btn--ghost aid-action" href="aide-detail.php?t=lettre">Voir plus</a>
            <div class="card-body">
                <p><em>Courte, concrète, tournée « entreprise »</em></p>
                <ul>
                    <li>Structure simple : <strong>Pourquoi eux → Pourquoi toi → Ce que tu proposes</strong>.</li>
                    <li>Personnalise (nom de l’entreprise, missions, technologies).</li>
                    <li>Style direct, évite les phrases trop longues et le “copier-coller”.</li>
                </ul>
                <p class="help">
                    Ressources :
                    <a href="docs/REGLES_LM.pdf" target="_blank" rel="noopener">Règles LM</a> ·
                    <a href="docs/MAIL_PROFESSIONNEL.pdf" target="_blank" rel="noopener">Mail professionnel</a>
                </p>
            </div>
        </article>

        <article class="card aid-card">
            <div class="card-header">
                <h2>Mail de candidature</h2>
            </div>
            <a class="btn btn--ghost aid-action" href="aide-detail.php?t=mail">Voir plus</a>
            <div class="card-body">
                <p><em>Objet clair, ton professionnel</em></p>
                <ul>
                    <li><strong>Objet</strong> : “Candidature stage GEII - Automatisme - Avril 2025”.</li>
                    <li>Message court : qui tu es, ce que tu demandes, tes points forts + CV/LM joints.</li>
                    <li>Signature complète : nom, formation, téléphone, lien LinkedIn.</li>
                </ul>
                <p class="help">
                    Ressource : <a href="docs/MAIL_PROFESSIONNEL.pdf" target="_blank" rel="noopener">Mail
                        professionnel</a>
                </p>
            </div>
        </article>

        <article class="card aid-card">
            <div class="card-header">
                <h2>CV vidéo (option)</h2>
            </div>
            <a class="btn btn--ghost aid-action" href="aide-detail.php?t=cvvideo">Voir plus</a>
            <div class="card-body">
                <p><em>Court, authentique, structuré</em></p>
                <ul>
                    <li>Durée 60-90 s. Présente-toi, ton projet, ta valeur → invite à la discussion.</li>
                    <li>Soin image/son ; prépare un script, reste naturel.</li>
                </ul>
                <p class="help">
                    Ressource : <a href="docs/CREER_CV_VIDEO.pdf" target="_blank" rel="noopener">Créer un CV vidéo</a>
                </p>
            </div>
        </article>

        <article class="card aid-card">
            <div class="card-header">
                <h2>Objectifs Stage / Alternance</h2>
            </div>
            <a class="btn btn--ghost aid-action" href="aide-detail.php?t=objectifs">Voir plus</a>
            <div class="card-body">
                <ul>
                    <li>Compétences à acquérir, livrables concrets, jalons.</li>
                    <li>Indicateurs + suivi tuteur → bilan.</li>
                </ul>
                <p class="help">
                    Ressource : <a href="docs/DOC1_OBJECTIFS_ALTERNANCE_STAGE.pdf" target="_blank"
                        rel="noopener">Définir les objectifs</a>
                </p>
            </div>
        </article>

        <article id="entretien" class="card aid-card">
            <div class="card-header">
                <h2>Réussir l’entretien</h2>
            </div>
            <a class="btn btn--ghost aid-action" href="aide-detail.php?t=entretien">Voir plus</a>
            <div class="card-body">
                <ul>
                    <li>Prépare ton pitch (Parcours → Projet → Pourquoi l’entreprise).</li>
                    <li>Exemples concrets (projets, pannes, résultats, ce que tu as appris).</li>
                    <li>Questions à poser (missions, techno, encadrement, suite).</li>
                </ul>
                <p class="help">
                    Ressource : <a href="docs/COURS_ENTRETIEN_MOTIVATION.pdf" target="_blank" rel="noopener">Cours
                        entretien & motivation</a>
                </p>
            </div>
        </article>

        <article id="liens" class="card aid-card">
            <div class="card-header">
                <h2>Sitographie utile</h2>
            </div>
            <a class="btn btn--ghost aid-action" href="aide-detail.php?t=sitographie">Voir plus</a>
            <div class="card-body">
                <ul>
                    <li>Gabarits CV/LM, simulateurs d’entretien, sites d’offres, chaînes RH.</li>
                </ul>
                <p class="help">
                    Ressource : <a href="docs/sitographie_cvlm.pdf" target="_blank" rel="noopener">Sitographie CV/LM</a>
                </p>
            </div>
        </article>

        <article id="docs" class="card aid-card">
            <div class="card-header">
                <h2>Documents utiles</h2>
            </div>
            <a class="btn btn--ghost aid-action" href="aide-detail.php?t=docs">Voir plus</a>
            <div class="card-body">
                <ul>
                    <li><a href="../docs/CREER_CV_VIDEO.pdf" target="_blank" rel="noopener">Créer un CV vidéo</a></li>
                    <li><a href="../docs/COURS_ENTRETIEN_MOTIVATION.pdf" target="_blank" rel="noopener">Cours :
                            entretien &
                            motivation</a></li>
                    <li><a href="../docs/REGLES_LM.pdf" target="_blank" rel="noopener">Règles de la lettre de
                            motivation</a></li>
                    <li><a href="../docs/MAIL_PROFESSIONNEL.pdf" target="_blank" rel="noopener">Écrire un mail
                            professionnel</a></li>
                    <li><a href="../docs/DOC1_OBJECTIFS_ALTERNANCE_STAGE.pdf" target="_blank" rel="noopener">Objectifs
                            Stage / Alternance</a></li>
                    <li><a href="../docs/doc_etudiant_preparation_entretien.pdf" target="_blank"
                            rel="noopener">Préparation
                            entretien (étudiant)</a></li>
                    <li><a href="../docs/TECHNIQUES_ARGUMENTATION.pdf" target="_blank" rel="noopener">Techniques
                            d’argumentation</a></li>
                </ul>
                <p class="help">Ces fichiers PDF sont consultables librement.</p>
            </div>
        </article>


    </div>
</main>

<?php include dirname(__DIR__) . '/partials/footer.php'; ?>
</body>

</html>