<?php
// aide-detail.php - contenu détaillé pour chaque thème
$topic = $_GET['t'] ?? 'cv';

$pages = [
  // Définitions des pages (CV, Lettre, Mail, etc.)
  // Le contenu $pages['cv'], $pages['lettre'], etc. Reste le même car il est bien structuré.
  // ... (Contenu du tableau $pages inchangé) ...
  'cv' => [
    'title' => 'Créer un CV efficace',
    'intro' => "Un CV lisible et ciblé augmente fortement ton taux de réponse. Objectif : montrer vite ce que tu sais FAIRE.",
    'sections' => [
      [
        'h' => 'Structure recommandée',
        'p' => '
<ul>
  <li><strong>En-tête</strong> : Nom, téléphone, email .edu/univ si possible, LinkedIn/GitHub (liens cliquables).</li>
  <li><strong>Accroche</strong> (2-3 lignes) : « Étudiant GEII, recherche stage <em>Automatisme/Énergie</em> - je sais X, j’ai réalisé Y ».</li>
  <li><strong>Compétences</strong> : techniques (Automatisme, C/C++, Python, électrotech…), outils (TIA Portal, LabVIEW…), soft skills prouvées par des faits.</li>
  <li><strong>Expériences/Projets</strong> : 3-4 items max, chacun avec <em>contexte → actions → résultats</em> (métriques).</li>
  <li><strong>Formation</strong> : diplôme, options, mentions utiles.</li>
  <li><strong>Plus</strong> : langues, permis, asso, certifs.</li>
</ul>'
      ],
      [
        'h' => 'Règles d’or',
        'p' => '
<ul>
  <li>1 page, aérée, marges cohérentes, typographie sobre.</li>
  <li>Orthographe irréprochable, noms de fichiers propres (<code>CV_PrenomNom.pdf</code>).</li>
  <li>Preuves & chiffres : « banc de test → -25 % temps de mesure », « dashboard énergie »…</li>
  <li>Priorise ce qui colle à l’offre. Élaguer le reste.</li>
</ul>'
      ],
      [
        'h' => 'Exemples de puces impactantes',
        'p' => '
<ul>
  <li>Programmation <strong>API Siemens S7-1200</strong> (TIA Portal) : séquences, HMI, alarmes.</li>
  <li>Prototype <strong>ESP32</strong> + capteurs → <strong>MQTT</strong>, dashboard Grafana (suivi conso).</li>
  <li>Conception armoire <strong>CEI 60204-1</strong>, schémas <strong>SEE Electrical</strong>.</li>
</ul>'
      ],
    ],
    'see' => [
      ['t' => 'Lettre de motivation', 'href' => '/Annuaire/pages/aide-detail.php?t=lettre'],
      ['t' => 'Mail professionnel', 'href' => '/Annuaire/pages/aide-detail.php?t=mail'],
      ['t' => 'Documents utiles', 'href' => '/Annuaire/pages/aide-detail.php?t=docs'],
    ],
  ],

  'lettre' => [
    'title' => 'Lettre de motivation',
    'intro' => "Courte, concrète, orientée entreprise. Vise l’humain qui lit, pas un robot.",
    'sections' => [
      [
        'h' => 'Structure simple (≈ 12 lignes)',
        'p' => '
<ul>
  <li><strong>1) Pourquoi eux</strong> : référence à leurs projets/secteur/site.</li>
  <li><strong>2) Pourquoi toi</strong> : 2-3 preuves (projet, stage, compétence rare).</li>
  <li><strong>3) Ce que tu proposes</strong> : valeur, disponibilité, mobilité.</li>
</ul>'
      ],
      [
        'h' => 'Ton & style',
        'p' => '
<ul>
  <li>Évite le verbiage. Précise, concret, poli.</li>
  <li>Pas de copier/coller : <em>adapter</em> à l’offre.</li>
  <li>Signature complète + PJ nommées.</li>
</ul>'
      ],
    ],
    'see' => [
      ['t' => 'Créer un CV', 'href' => '/Annuaire/pages/aide-detail.php?t=cv'],
      ['t' => 'Mail professionnel', 'href' => '/Annuaire/pages/aide-detail.php?t=mail'],
      ['t' => 'Documents utiles', 'href' => '/Annuaire/pages/aide-detail.php?t=docs'],
    ],
  ],

  'mail' => [
    'title' => 'Mail professionnel',
    'intro' => "Ton mail est la première impression. Il doit donner envie d’ouvrir ton CV/LM.",
    'sections' => [
      [
        'h' => 'Bonnes pratiques',
        'p' => '
<ul>
  <li><strong>Objet</strong> : « Candidature Stage GEII - Automatisme - Avril 2025 ».</li>
  <li>Corps court (5-7 lignes) : qui tu es, ce que tu veux, ce que tu apportes, dispo.</li>
  <li>Formules pro, une ou deux pièces jointes PDF (CV/LM) correctement nommées.</li>
  <li>Signature : Nom - IUT GEII - tél - LinkedIn.</li>
</ul>'
      ],
      [
        'h' => 'Relance',
        'p' => '
<ul>
  <li>J+7/J+10 : « Bonjour, je me permets de relancer au sujet de ma candidature envoyée le XX… ».</li>
</ul>'
      ],
    ],
    'see' => [
      ['t' => 'Lettre de motivation', 'href' => '/Annuaire/pages/aide-detail.php?t=lettre'],
      ['t' => 'Documents utiles', 'href' => '/Annuaire/pages/aide-detail.php?t=docs'],
    ],
  ],

  'entretien' => [
    'title' => "Préparer l’entretien",
    'intro' => "Objectif : être clair, concret, sympathique. Préparation = sérénité.",
    'sections' => [
      [
        'h' => 'Avant',
        'p' => '
<ul>
  <li>Pitch 60-90 s (parcours → projet → valeur).</li>
  <li>Révisions : projets (chiffres/contraintes), bases techniques de l’offre.</li>
  <li>Questions à poser : missions, environnement, stack/outils, suite du process.</li>
</ul>'
      ],
      [
        'h' => 'Pendant',
        'p' => '
<ul>
  <li>Méthode <strong>STAR</strong> : Situation → Tâche → Action → Résultat.</li>
  <li>Illustrer par des faits. Noter les infos clés.</li>
</ul>'
      ],
      [
        'h' => 'Après',
        'p' => '
<ul>
  <li>Mail de remerciement bref (dans la journée).</li>
  <li>Suivi à J+5 si pas de nouvelles.</li>
</ul>'
      ],
    ],
    'see' => [
      ['t' => 'Techniques d’argumentation', 'href' => '/Annuaire/pages/aide-detail.php?t=argumentation'],
      ['t' => 'Documents utiles', 'href' => '/Annuaire/pages/aide-detail.php?t=docs'],
    ],
  ],

  'argumentation' => [
    'title' => 'Techniques d’argumentation',
    'intro' => "Convaincre = relier besoins de l’entreprise et tes preuves.",
    'sections' => [
      [
        'h' => 'CAP & STAR',
        'p' => '
<ul>
  <li><strong>CAP</strong> : Caractéristique → Avantage → Preuve.</li>
  <li><strong>STAR</strong> : Situation → Tâche → Action → Résultat (mesures).</li>
</ul>'
      ],
      [
        'h' => 'Répondre aux objections',
        'p' => '
<ul>
  <li>Reformuler, donner un exemple mesuré, proposer une alternative.</li>
</ul>'
      ],
    ],
    'see' => [
      ['t' => 'Entretien', 'href' => '/Annuaire/pages/aide-detail.php?t=entretien'],
      ['t' => 'Documents utiles', 'href' => '/Annuaire/pages/aide-detail.php?t=docs'],
    ],
  ],

  'cvvideo' => [
    'title' => 'CV vidéo (option)',
    'intro' => "Court, authentique, tourné « propre ». Toujours <em>en complément</em> d’un CV PDF.",
    'sections' => [
      [
        'h' => 'Tournage',
        'p' => '
<ul>
  <li>60-90 s, script léger, sourire, fond simple, bonne lumière/son.</li>
  <li>Structure : Qui tu es → Ce que tu sais faire → Pourquoi cette entreprise → Call-to-action.</li>
</ul>'
      ],
      [
        'h' => 'Diffusion',
        'p' => '
<ul>
  <li>Non répertorié YouTube → lien/QR sur ton CV ou en signature mail.</li>
</ul>'
      ],
    ],
    'see' => [
      ['t' => 'Créer un CV', 'href' => '/Annuaire/pages/aide-detail.php?t=cv'],
      ['t' => 'Documents utiles', 'href' => '/Annuaire/pages/aide-detail.php?t=docs'],
    ],
  ],

  'objectifs' => [
    'title' => 'Objectifs Stage/Alternance',
    'intro' => "Clarifie <em>ce que tu veux apprendre</em> et <em>ce que tu livreras</em>.",
    'sections' => [
      [
        'h' => 'Formuler des objectifs',
        'p' => '
<ul>
  <li>Compétences attendues (techniques & transverses).</li>
  <li>Livrables concrets (POC, rapport, mode opératoire, MCD, script test…).</li>
  <li>Indicateurs de réussite et jalons.</li>
</ul>'
      ],
      [
        'h' => 'Suivi',
        'p' => '
<ul>
  <li>Points réguliers avec tuteur, adaptation si besoin, bilan final co-rédigé.</li>
</ul>'
      ],
    ],
    'see' => [
      ['t' => 'Documents utiles', 'href' => '/Annuaire/pages/aide-detail.php?t=docs'],
    ],
  ],

  'docs' => [
    'title' => 'Documents utiles',
    'intro' => "Tous les supports pour t’aider à candidater : CV, lettre, mail, entretien, objectifs, etc.",
    'sections' => [
      [
        'h' => 'Téléchargements (PDF)',
        'p' => '
  <ul class="doc-list">
    <li><a href="docs/CREER_CV_VIDEO.pdf" target="_blank" rel="noopener"><span class="ico">📄</span> Créer un CV vidéo</a></li>
    <li><a href="docs/COURS_ENTRETIEN_MOTIVATION.pdf" target="_blank" rel="noopener"><span class="ico">📄</span> Cours : entretien & motivation</a></li>
    <li><a href="docs/REGLES_LM.pdf" target="_blank" rel="noopener"><span class="ico">📄</span> Règles de la lettre de motivation</a></li>
    <li><a href="docs/MAIL_PROFESSIONNEL.pdf" target="_blank" rel="noopener"><span class="ico">📄</span> Mail professionnel</a></li>
    <li><a href="docs/DOC1_OBJECTIFS_ALTERNANCE_STAGE.pdf" target="_blank" rel="noopener"><span class="ico">📄</span> Objectifs Stage / Alternance</a></li>
    <li><a href="docs/doc_etudiant_preparation_entretien.pdf" target="_blank" rel="noopener"><span class="ico">📄</span> Préparation entretien (étudiant)</a></li>
    <li><a href="docs/TECHNIQUES_ARGUMENTATION.pdf" target="_blank" rel="noopener"><span class="ico">📄</span> Techniques d’argumentation</a></li>
    <li><a href="docs/sitographie_cvlm.pdf" target="_blank" rel="noopener"><span class="ico">📄</span> Sitographie CV/LM</a></li>
  </ul>'
      ],
    ],
    'see' => [
      ['t' => 'Créer un CV', 'href' => 'aide-detail.php?t=cv'],
      ['t' => 'Lettre de motivation', 'href' => 'aide-detail.php?t=lettre'],
      ['t' => 'Mail professionnel', 'href' => 'aide-detail.php?t=mail'],
      ['t' => 'Préparer l’entretien', 'href' => 'aide-detail.php?t=entretien'],
    ],
  ],

  'sitographie' => [
    'title' => 'Sitographie utile',
    'intro' => "Des ressources pour aller plus loin.",
    'sections' => [
      [
        'h' => 'Inspiration & outils',
        'p' => '
<ul>
  <li>Gabarits CV/LM (par ex. Canva) ; simulateurs d’entretiens (OpenClassrooms, YouTube RH).</li>
  <li>Offres : LinkedIn, Welcome to the Jungle, APEC, sites d’entreprises locales.</li>
  <li>Voir aussi la <a href="aide-detail.php?t=docs">page Documents utiles</a>.</li>
</ul>'
      ],
    ],
  ],

];

$cur = $pages[$topic] ?? $pages['cv'];
?>
<!doctype html>
<html lang="fr" data-theme="<?php echo isset($_COOKIE['theme']) ? htmlspecialchars($_COOKIE['theme']) : 'light'; ?>">

<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($cur['title']) ?> - Aide GEII</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../assets/css/style.css?v=aide-detail-final">
</head>

<body>
  <?php
  $page_title = $cur['title'] . ' · Aide GEII';
  $page_description = 'Guide détaillé : ' . $cur['intro'];
  include dirname(__DIR__) . '/partials/header.php';
  ?>
  <div class="header-spacer"></div>

  <main class="main-detail-aide">
    <nav class="aide-detail-nav">
      <a class="btn outline" href="aide.php">← Retour au centre d’aide</a>
    </nav>

    <article class="card detail-card">
      <div class="card-header">
        <h1 class="title"><?= htmlspecialchars($cur['title']) ?></h1>
        <p class="subtitle"><?= $cur['intro'] ?></p>
      </div>

      <div class="card-body detail-content">
        <?php foreach ($cur['sections'] as $s): ?>
          <section class="detail-section">
            <h2><?= htmlspecialchars($s['h']) ?></h2>
            <div class="detail-section-content">
              <?= $s['p'] ?>
            </div>
          </section>
        <?php endforeach; ?>

        <?php if (!empty($cur['see'])): ?>
          <div class="detail-see-also">
            <h3>Voir aussi :</h3>
            <div class="actions detail-actions">
              <?php foreach ($cur['see'] as $l): ?>
                <a class="btn ghost" href="<?= htmlspecialchars($l['href']) ?>">→ <?= htmlspecialchars($l['t']) ?></a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </article>
  </main>

  <?php include dirname(__DIR__) . '/partials/footer.php'; ?>
</body>

</html>