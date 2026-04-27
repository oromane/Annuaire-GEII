<?php
// terms.php - Page Conditions d'utilisation / Mentions Légales
$page_title = 'Mentions Légales & Conditions d\'Utilisation · Annuaire GEII';
$page_description = 'Consultez les mentions légales et les conditions d\'utilisation de l\'Annuaire des Expériences GEII de l\'IUT de Lille.';
require dirname(__DIR__) . '/partials/header.php'; // Inclut le header commun
?>
<div class="header-spacer"></div>

<main class="main-legal">
    <section class="legal-hero">
        <h1>Mentions Légales & Conditions d'Utilisation</h1>
        <p class="subtitle">Informations relatives à l'Annuaire des Expériences GEII</p>
    </section>

    <section class="card card--static legal-card">
        <div class="card-body">

            <h2>Éditeur</h2>
            <p>
                Ce site "Annuaire des Expériences GEII" est une initiative interne au département GEII de l'IUT de
                Lille.
                Il est géré par les administrateurs désignés au sein du département.
            </p>
            <p>
                Pour toute question relative à ce site, veuillez utiliser la page <a
                    href="/Annuaire/pages/contact.php">Contact</a>.
            </p>

            <h2>Réalisation</h2>
            <p>
                Ce site a été conçu et développé de A à Z par
                <strong>ROSSIGNOL Romane</strong>,
                étudiante en BUT GEII à l'IUT de Lille.
                Ce projet répond à un besoin réel identifié par les étudiant.e.s
                de la formation : centraliser les retours d'expériences de stages et d'alternances
                pour aider les promotions suivantes dans leurs recherches.
            </p>
            <p>
                <a href="https://www.linkedin.com/in/rossignol-romane/" target="_blank"
                    rel="noopener noreferrer">LinkedIn</a>
                &nbsp;&middot;&nbsp;
                <a href="https://github.com/oromane/" target="_blank" rel="noopener noreferrer">GitHub</a>
            </p>

            <h2>Hébergement</h2>
            <p>
                Le site est hébergé sur les infrastructures informatiques de l'Université de Lille, gérées par la
                Direction des Systèmes d'Information (DSI).
            </p>

            <h2>Responsabilité Éditoriale</h2>
            <p>
                Le contenu éditorial initial est fourni par les responsables du projet Annuaire GEII.
                Le contenu des expériences (stages, alternances) est soumis par les étudiant.e.s et ancien.ne.s étudiant.e.s sous
                leur propre responsabilité.
            </p>

            <h2>Propriété Intellectuelle</h2>
            <p>
                La structure générale du site, ainsi que les textes et éléments graphiques (hors logos institutionnels
                et contenu soumis par les utilisateurs) sont la propriété du projet Annuaire GEII.
            </p>
            <p>
                Les contenus soumis par les utilisateurs (descriptions d'expériences, CV, lettres de motivation,
                rapports) restent la propriété de leurs auteurs respectifs. En soumettant du contenu, l'utilisateur
                accorde au projet Annuaire GEII le droit non exclusif de le diffuser au sein de cet annuaire à des fins
                pédagogiques et informatives internes à l'IUT.
            </p>
            <p>
                La reproduction de tout ou partie de ce site sur un support électronique quel qu'il soit est
                formellement interdite sauf autorisation expresse des responsables du projet. Les logos de l'Université
                de Lille et de l'IUT de Lille sont utilisés avec autorisation et restent leur propriété exclusive.
            </p>

            <h2>Avertissement sur le Contenu</h2>
            <p>
                L'équipe administrative de l'Annuaire GEII s'efforce d'assurer au mieux l'exactitude des informations
                diffusées sur ce site. Cependant, une grande partie du contenu provient des soumissions des
                utilisateurs. L'équipe ne peut garantir l'exactitude, la précision ou l'exhaustivité de toutes les
                informations fournies par les utilisateurs.
            </p>
            <p>
                En conséquence, l'Annuaire GEII décline toute responsabilité :
            <ul>
                <li>Pour toute interruption éventuelle du site ;</li>
                <li>Pour toute inexactitude ou omission portant sur les informations disponibles, notamment celles
                    soumises par les utilisateurs ;</li>
                <li>Pour tous dommages résultant d'une utilisation des informations contenues sur ce site.</li>
            </ul>
            Chaque utilisateur est responsable de la vérification des informations avant de les utiliser, notamment dans
            le cadre d'une candidature.
            </p>

            <h2>Données Personnelles</h2>
            <p>
                Les informations personnelles collectées via le formulaire d'ajout d'expérience (nom, prénom, email,
                liens LinkedIn) ne sont utilisées que dans le cadre de l'Annuaire GEII et ne sont pas partagées avec des
                tiers sans consentement explicite. L'affichage de ces informations sur la fiche détail est conditionné
                par les choix de l'utilisateur lors de la soumission (si une telle option existe) ou par défaut interne.
            </p>
            <p>
                Conformément à la réglementation en vigueur, vous disposez d'un droit d'accès, de rectification et de
                suppression des données vous concernant. Vous pouvez exercer ce droit en contactant les administrateurs
                via la page <a href="/Annuaire/pages/contact.php">Contact</a>.
            </p>

            <h2>Cookies</h2>
            <p>
                Ce site utilise des cookies techniques pour améliorer l'expérience utilisateur, notamment pour mémoriser
                votre préférence de thème (clair/sombre). Aucun cookie de suivi publicitaire ou d'analyse tierce n'est
                utilisé sans votre consentement. Vous pouvez configurer votre navigateur pour refuser les cookies si
                vous le souhaitez.
            </p>

            <p class="muted-text"
                style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--color-border); font-size: 0.85rem;">
                <i>Dernière mise à jour : 14 mars 2026. Site réalisé par ROSSIGNOL Romane, étudiante BUT GEII IUT de
                    Lille.</i>
            </p>

        </div>
    </section>

</main>

<?php include dirname(__DIR__) . '/partials/footer.php'; ?>
</body>

</html>