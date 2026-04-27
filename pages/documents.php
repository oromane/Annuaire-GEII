<?php
// aide.php - Centre d'aide complet 
$page_title = 'Documents - Annuaire GEII';
$page_description = 'Consulte nos documents et conseils pour réussir ton CV, ta lettre de motivation, tes entretiens et ta recherche de stage ou d\'alternance.';
require dirname(__DIR__) . '/partials/header.php';
?>
<div class="header-spacer"></div>

<main class="main-aide">
    <section class="help-hero">
        <h1>Documents &amp; Conseils</h1>
        <p>Ta boîte à outils pour décrocher ton stage ou ton alternance ! On a rassemblé ici tous les documents,
            modèles et conseils pour t'accompagner à chaque étape de ta candidature.
            Du CV à l'entretien, tout est couvert. Profites-en, c'est fait pour toi ! 💪</p>

        <nav class="help-chips" aria-label="Raccourcis">
            <a class="chip" href="#preparer">Préparer sa candidature</a>
            <a class="chip" href="#entretien">Réussir l'entretien</a>
            <a class="chip" href="#docs">Documents utiles</a>
            <a class="chip" href="#liens">Liens externes</a>
        </nav>
    </section>

    <div class="aide-grid">
        <article id="preparer" class="card card--static aid-card">
            <div class="card-header">
                <h2>Créer un CV efficace</h2>
            </div>
            <a class="btn btn--ghost aid-action" href="/Annuaire/pages/documents-detail.php?t=cv">Voir plus</a>
            <div class="card-body">
                <p><em>Lisible, ciblé, professionnel</em></p>
                <ul>
                    <li>1 page claire, avec un <strong>objectif</strong> accrocheur lié au poste.</li>
                    <li>Mets en avant <strong>projets</strong>, <strong>compétences</strong> (automatisme, IoT,
                        énergie…), <strong>outils</strong> et <strong>réalisations</strong>.</li>
                    <li>Hiérarchise : expériences récentes en premier, dates alignées.</li>
                    <li>Relis : orthographe, mail pro, liens (GitHub/LinkedIn) valides.</li>
                </ul>
                <div class="aid-resources">
                    <a href="/Annuaire/docs/sitographie_cvlm.pdf" target="_blank" rel="noopener"
                        class="btn outline resource-btn">Sitographie CV &amp; LM</a>
                    <a href="/Annuaire/docs/CREER_CV_VIDEO.pdf" target="_blank" rel="noopener"
                        class="btn outline resource-btn">Créer un CV vidéo</a>
                </div>
            </div>
        </article>

        <article class="card card--static aid-card">
            <div class="card-header">
                <h2>Lettre de motivation</h2>
            </div>
            <a class="btn btn--ghost aid-action" href="/Annuaire/pages/documents-detail.php?t=lettre">Voir plus</a>
            <div class="card-body">
                <p><em>Courte, concrète, tournée « entreprise »</em></p>
                <ul>
                    <li>Structure simple : <strong>Pourquoi eux → Pourquoi toi → Ce que tu proposes</strong>.</li>
                    <li>Personnalise (nom de l'entreprise, missions, technologies).</li>
                    <li>Style direct, évite les phrases trop longues et le "copier-coller".</li>
                </ul>
                <div class="aid-resources">
                    <a href="/Annuaire/docs/REGLES_LM.pdf" target="_blank" rel="noopener"
                        class="btn outline resource-btn">Règles LM</a>
                    <a href="/Annuaire/docs/MAIL_PROFESSIONNEL.pdf" target="_blank" rel="noopener"
                        class="btn outline resource-btn">Mail professionnel</a>
                </div>
            </div>
        </article>

        <article class="card card--static aid-card">
            <div class="card-header">
                <h2>Mail de candidature</h2>
            </div>
            <a class="btn btn--ghost aid-action" href="/Annuaire/pages/documents-detail.php?t=mail">Voir plus</a>
            <div class="card-body">
                <p><em>Objet clair, ton professionnel</em></p>
                <ul>
                    <li><strong>Objet</strong> : "Candidature stage GEII - Automatisme - Avril 2025".</li>
                    <li>Message court : qui tu es, ce que tu demandes, tes points forts + CV/LM joints.</li>
                    <li>Signature complète : nom, formation, téléphone, lien LinkedIn.</li>
                </ul>
                <div class="aid-resources">
                    <a href="/Annuaire/docs/MAIL_PROFESSIONNEL.pdf" target="_blank" rel="noopener"
                        class="btn outline resource-btn">Mail professionnel</a>
                </div>
            </div>
        </article>

        <article class="card card--static aid-card">
            <div class="card-header">
                <h2>CV vidéo (option)</h2>
            </div>
            <a class="btn btn--ghost aid-action" href="/Annuaire/pages/documents-detail.php?t=cvvideo">Voir plus</a>
            <div class="card-body">
                <p><em>Court, authentique, structuré</em></p>
                <ul>
                    <li>Durée 60-90 s. Présente-toi, ton projet, ta valeur → invite à la discussion.</li>
                    <li>Soin image/son ; prépare un script, reste naturel.</li>
                </ul>
                <div class="aid-resources">
                    <a href="/Annuaire/docs/CREER_CV_VIDEO.pdf" target="_blank" rel="noopener"
                        class="btn outline resource-btn">Créer un CV vidéo</a>
                </div>
            </div>
        </article>

        <article class="card card--static aid-card">
            <div class="card-header">
                <h2>Objectifs Stage / Alternance</h2>
            </div>
            <a class="btn btn--ghost aid-action" href="/Annuaire/pages/documents-detail.php?t=objectifs">Voir plus</a>
            <div class="card-body">
                <ul>
                    <li>Compétences à acquérir, livrables concrets, jalons.</li>
                    <li>Indicateurs + suivi tuteur → bilan.</li>
                </ul>
                <div class="aid-resources">
                    <a href="/Annuaire/docs/DOC1_OBJECTIFS_ALTERNANCE_STAGE.pdf" target="_blank" rel="noopener"
                        class="btn outline resource-btn">Définir les objectifs</a>
                </div>
            </div>
        </article>

        <article id="entretien" class="card card--static aid-card">
            <div class="card-header">
                <h2>Réussir l'entretien</h2>
            </div>
            <a class="btn btn--ghost aid-action" href="/Annuaire/pages/documents-detail.php?t=entretien">Voir plus</a>
            <div class="card-body">
                <ul>
                    <li>Prépare ton pitch (Parcours → Projet → Pourquoi l'entreprise).</li>
                    <li>Exemples concrets (projets, pannes, résultats, ce que tu as appris).</li>
                    <li>Questions à poser (missions, techno, encadrement, suite).</li>
                </ul>
                <div class="aid-resources">
                    <a href="/Annuaire/docs/COURS_ENTRETIEN_MOTIVATION.pdf" target="_blank" rel="noopener"
                        class="btn outline resource-btn">Cours entretien &amp; motivation</a>
                </div>
            </div>
        </article>

        <article id="liens" class="card card--static aid-card">
            <div class="card-header">
                <h2>Sitographie utile</h2>
            </div>
            <a class="btn btn--ghost aid-action" href="/Annuaire/pages/documents-detail.php?t=sitographie">Voir plus</a>
            <div class="card-body">
                <ul>
                    <li>Gabarits CV/LM, simulateurs d'entretien, sites d'offres, chaînes RH.</li>
                </ul>
                <div class="aid-resources">
                    <a href="/Annuaire/docs/sitographie_cvlm.pdf" target="_blank" rel="noopener"
                        class="btn outline resource-btn">Sitographie CV/LM</a>
                </div>
            </div>
        </article>

        <article id="docs" class="card card--static aid-card">
            <div class="card-header">
                <h2>Documents utiles</h2>
            </div>
            <a class="btn btn--ghost aid-action" href="/Annuaire/pages/documents-detail.php?t=docs">Voir plus</a>
            <div class="card-body">
                <div class="aid-resources doc-list-resources">
                    <a href="../docs/CREER_CV_VIDEO.pdf" target="_blank" rel="noopener"
                        class="btn outline resource-btn">Créer un CV vidéo</a>
                    <a href="../docs/COURS_ENTRETIEN_MOTIVATION.pdf" target="_blank" rel="noopener"
                        class="btn outline resource-btn">Cours : entretien &amp; motivation</a>
                    <a href="../docs/REGLES_LM.pdf" target="_blank" rel="noopener"
                        class="btn outline resource-btn">Règles de la lettre de motivation</a>
                    <a href="../docs/MAIL_PROFESSIONNEL.pdf" target="_blank" rel="noopener"
                        class="btn outline resource-btn">Écrire un mail professionnel</a>
                    <a href="../docs/DOC1_OBJECTIFS_ALTERNANCE_STAGE.pdf" target="_blank" rel="noopener"
                        class="btn outline resource-btn">Objectifs Stage / Alternance</a>
                    <a href="../docs/doc_etudiant_preparation_entretien.pdf" target="_blank" rel="noopener"
                        class="btn outline resource-btn">Préparation entretien (étudiant)</a>
                    <a href="../docs/TECHNIQUES_ARGUMENTATION.pdf" target="_blank" rel="noopener"
                        class="btn outline resource-btn">Techniques d'argumentation</a>
                </div>
                <p class="help">Ces fichiers PDF sont consultables librement.</p>
            </div>
        </article>


    </div>
</main>

<?php include dirname(__DIR__) . '/partials/footer.php'; ?>
</body>

</html>