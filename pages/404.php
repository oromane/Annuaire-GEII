<?php
// 404.php - Page d'erreur personnalisée et amusante
http_response_code(404);
$page_title = 'Erreur 404 - Perdu dans l\'espace GEII ?';
require dirname(__DIR__) . '/partials/header.php';

// Le chemin $BASE est défini dans le header.php
$BASE = '/Annuaire';

// Liste de messages drôles et geek
$funny_messages = [
    "Oups ! On dirait que la page s'est perdue quelque part entre deux lignes de code C++.",
    "Erreur 404 : Page non trouvée. Peut-être que le stage était tellement bon qu'elle ne voulait plus revenir ?",
    "Houston, nous avons un problème... La page demandée n'a pas pu être localisée. Dernier signal : un microcontrôleur en surchauffe.",
    "Cette page a dû être prise en otage par un robot récalcitrant. Envoyez les renforts !",
    "404 Not Found. Avez-vous vérifié sous le capot ? Des fois, ça se cache derrière un composant électronique.",
    "La page est partie en pause-café... pour l'éternité. Désolé pour le dérangement.",
];
$random_message = $funny_messages[array_rand($funny_messages)];

// Liste de faits amusants GEII, Lille et Technologie
// Liste de faits amusants GEII, Lille et Technologie
$fun_facts = [
    // Électricité / Électronique
    "L'ampère est une unité de base du Système international. Saviez-vous que 1 Ampère équivaut à un flux d'environ <strong>6.24 milliards de milliards d'électrons par seconde</strong> ?",
    "Le <strong>transistor</strong>, inventé en 1947, est la brique de base de toute l'électronique moderne. Votre smartphone en contient des milliards !",
    "La fréquence standard du courant alternatif en Europe est <strong>50 Hz</strong>. Aux États-Unis, c'est <strong>60 Hz</strong>.",

    // Automatisme / Robotique
    "Le terme 'Robot' vient du mot tchèque <strong>'Robota'</strong>, qui signifie 'travail forcé' ou 'corvée'.",
    "Les <strong>systèmes PID</strong> (Proportionnel, Intégral, Dérivé) sont la base de 90% des systèmes de contrôle en automatisation industrielle. Un classique du GEII !",
    "Une ligne de production entièrement automatisée peut atteindre une précision et une vitesse impossibles pour l'humain. C'est l'art de l'<strong>Automatisme</strong>.",

    // Informatique / Programmation
    "Le <strong>Bug</strong> le plus célèbre de l'histoire, le Bug de l'an 2000 (Y2K), était dû au fait que les programmeurs utilisaient seulement deux chiffres pour l'année (ex: '99').",
    "Le <strong>langage C</strong> est toujours essentiel en GEII : il permet de programmer efficacement les microcontrôleurs au plus proche du matériel.",
    "La loi de <strong>Moore</strong> (la densité des transistors double tous les deux ans) a guidé l'évolution de l'informatique pendant des décennies. Son ralentissement est le nouveau défi.",

    // Lille et Région
    "L'<strong>École Centrale de Lille</strong> est l'une des plus anciennes écoles d'ingénieurs de France, un lieu clé pour les poursuites d'études GEII !",
    "La <strong>métropole Lilloise</strong> est un pôle majeur de l'<strong>Automobile (Toyota)</strong> et de la <strong>Distribution (Auchan, Decathlon)</strong>, des secteurs qui recrutent massivement en GEII.",
    "Le premier <strong>métro entièrement automatique</strong> au monde, le <strong>VAL</strong> (Véhicule Automatique Léger), a été inauguré à Lille en 1983.",

    // BUT GEII
    "Le <strong>BUT GEII</strong> dure <strong>3 ans</strong> et confère le grade de Licence. Il est l'héritier du DUT et est reconnu par le monde industriel.",
    "Les compétences du GEII sont tellement variées qu'un diplômé peut travailler dans les <strong>énergies renouvelables</strong>, la <strong>santé connectée</strong>, ou l'<strong>aérospatiale</strong>.",
    "Un.e étudiant.e GEII passe plus de temps en <strong>laboratoire (TP)</strong> que dans n'importe quelle autre filière IUT. Le 'pratique' avant tout !"
];
$random_fact = $fun_facts[array_rand($fun_facts)];

?>
<div class="header-spacer"></div>

<main class="main-404">
    <section class="error-section">
        <div class="error-illustration">
            <img src="<?= $BASE ?>/assets/images/robot-404.gif" alt="Robot perdu 404" class="robot-gif">
        </div>

        <h1 class="error-title">Erreur 404 : Page Introuvable</h1>
        <p class="error-message"><?= htmlspecialchars($random_message, ENT_QUOTES) ?></p>
        <div class="error-actions">
            <a href="<?= $BASE ?>/index.php" class="btn primary btn-large">🚀 Retour à l'Accueil</a>
            <a href="<?= $BASE ?>/pages/annuaire.php" class="btn outline btn-large">🔍 Chercher une expérience</a>
        </div>

        <?php if ($random_fact): // Affiche un fait aléatoire si disponible ?>
            <div class="fun-fact">
                <p>💡 Fait amusant pour égayer votre journée :</p>
                <p><?= $random_fact ?></p>
            </div>
        <?php endif; ?>

    </section>
</main>

<?php require dirname(__DIR__) . '/partials/footer.php'; ?>