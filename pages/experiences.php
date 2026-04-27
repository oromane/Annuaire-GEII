<?php
// experiences.php - Fiche détail
$page_title = 'Détails de l\'expérience · Annuaire GEII';
$page_description = 'Retrouvez tous les détails de cette expérience de stage ou d\'alternance d\'un étudiant GEII : missions, outils, durée et contacts.';
require dirname(__DIR__) . '/partials/header.php';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
?>
<div class="header-spacer"></div>

<main class="main-experience">
    <section id="hero" class="experience-hero">
        <div class="exp-hero-nav">
            <a href="annuaire.php" class="btn outline exp-back-btn" aria-label="Retour à l'annuaire">← Retour</a>
        </div>
        <h1 id="heroTitle">Chargement…</h1>
        <div id="heroSub" class="hero-meta-chips">Veuillez patienter</div>
    </section>

    <section id="expCard" class="card card--static experience-card">
        <div class="card-header">
            <h2 class="title">Détails de l’expérience</h2>
            <p class="subtitle" id="miniMeta">-</p>
        </div>
        <div class="card-body">
            <div id="expBody" class="experience-body-grid">Récupération des données…</div>
        </div>
    </section>



</main>

<?php require dirname(__DIR__) . '/partials/footer.php'; ?>

<script>
    (function () {
        const ID = <?php echo $id; ?>;
        const $ = s => document.querySelector(s);

        if (!ID) {
            $('#heroTitle').textContent = 'Expérience introuvable';
            $('#heroSub').textContent = 'Identifiant manquant.';
            $('#expBody').innerHTML = '<div class="alert error">Impossible de charger la fiche.</div>';
            $('#commentsSection').style.display = 'none'; // Cache les commentaires
            return;
        }

        // Utils
        const esc = s => String(s ?? '').replace(/[&<>"']/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m]));
        const nl2br = s => esc(s).replace(/\n/g, '<br>');
        const chip = (label, value) => value ? `<span class="chip" title="${esc(label)}">${esc(value)}</span>` : '';
        const chipMini = (value) => value ? `<span class="chip chip--mini">${esc(value)}</span>` : '';
        const chips = csv => (csv || '').split(',').map(x => x.trim()).filter(Boolean).map(chipMini).join(' ');
        const btn = (label, href, icon = '→') => href ? `<a class="btn outline" href="${esc(href)}" target="_blank" rel="noopener">${esc(label)} ${icon}</a>` : '';
        const mailLink = v => (v && /@/.test(v)) ? `<a class="btn outline" href="mailto:${esc(v)}">${esc(v)}</a>` : '';

        fetch(`../api/experiences.php?id=${ID}`, { headers: { 'Accept': 'application/json' } })
            .then(r => {
                if (!r.ok) throw new Error(`Erreur HTTP ${r.status}`);
                return r.json();
            })
            .then(exp => {
                if (exp.error || !exp.id) { // Vérifie si l'ID existe dans la réponse
                    const errorMsg = exp.error || 'Expérience non trouvée.';
                    $('#heroTitle').textContent = 'Introuvable';
                    $('#heroSub').textContent = errorMsg;
                    $('#expBody').innerHTML = `<div class="alert error">${esc(errorMsg)}</div>`;
                    $('#commentsSection').style.display = 'none';
                    return;
                }

                // --- Héro ---
                $('#heroTitle').textContent = `${exp.entreprise_nom} - ${exp.poste}`;
                $('#heroSub').innerHTML = [
                    chip('Type', exp.type),
                    chip('Ville', exp.ville),
                    chip('Année', exp.annee),
                    chip('Durée', exp.duree_mois ? `${exp.duree_mois} mois` : null),
                    chip('Domaine', exp.domaine)
                ].filter(Boolean).join(' ');
                $('#miniMeta').textContent = `${exp.type} · ${exp.ville ?? '-'} · ${exp.annee}`;

                // --- Corps de fiche ---


                const isValidURL = (url) => {
                    if (!url) return false;
                    const s = String(url).trim().toLowerCase();
                    return s.startsWith('http') || s.startsWith('www') || s.includes('.com') || s.includes('.fr');
                };

                const linkOrText = (label, value) => {
                    if (!value || value === '-' || value === 'Pas de données') return null;
                    if (isValidURL(value)) {
                        let finalUrl = value.trim();
                        if (finalUrl.startsWith('www')) finalUrl = 'https://' + finalUrl;
                        return `<a class="btn outline" href="${esc(finalUrl)}" target="_blank" rel="noopener">${esc(label)} →</a>`;
                    }
                    // Si ce n'est pas une URL, on l'affiche en texte et on l'embellit (Capitalize)
                    const formatted = esc(value.trim()).split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase()).join(' ');
                    return `<div class="link-as-text" style="margin-top:0.5rem; font-size:0.95rem;"><strong>${esc(label)} :</strong> ${formatted}</div>`;
                };

                const mailLinkFixed = v => (v && /@/.test(v)) ? `<a class="btn outline" href="mailto:${esc(v.trim())}">${esc(v.trim())}</a>` : null;

                const linksHTML = [
                    mailLinkFixed(exp.etudiant_email),
                    linkOrText('LinkedIn Étudiant', exp.etudiant_linkedin),
                    linkOrText('Site Entreprise', exp.entreprise_site),
                    linkOrText('LinkedIn Entreprise', exp.entreprise_linkedin),
                    mailLinkFixed(exp.entreprise_contact)
                ].filter(Boolean).join(' ');

                const etudPrenom = exp.etudiant_prenom ? esc(exp.etudiant_prenom) : '';
                const etudNom = exp.etudiant_nom ? esc(exp.etudiant_nom) : '';

                // Helper: returns a field row only if value is present and not just "-" or empty spaces
                const hasVal = v => {
                    if (!v) return false;
                    const cleaned = String(v).trim();
                    return cleaned !== '' && cleaned !== '-' && !/^-+$/.test(cleaned) && cleaned !== 'Pas de données';
                };

                const fieldRow = (label, icon, content, fullWidth = false) => {
                    if (!content) return '';
                    return `<div class="field${fullWidth ? ' full-width' : ''}">
                        <label>${icon} ${label}</label>
                        <div>${content}</div>
                    </div>`;
                };

                const etudiantDisplay = [etudPrenom, etudNom].filter(Boolean).join(' ').trim() || null;
                const durée = exp.duree_mois ? `${esc(exp.type)} - ${esc(exp.duree_mois)} mois` : esc(exp.type);

                $('#expBody').innerHTML = [
                    fieldRow('Étudiant·e', '🎓', etudiantDisplay ? esc(etudiantDisplay) : null),
                    fieldRow('Type / Durée', '📋', durée),
                    fieldRow('Entreprise', '🏢', esc(exp.entreprise_nom)),
                    fieldRow('Ville / Année', '📍', `${hasVal(exp.ville) ? esc(exp.ville) : '?'} - ${esc(exp.annee)}`),
                    fieldRow('Adresse', '🗺️', hasVal(exp.entreprise_adresse) ? esc(exp.entreprise_adresse) : null),
                    fieldRow('Domaine', '⚙️', hasVal(exp.domaine) ? esc(exp.domaine) : null),
                    fieldRow('Missions', '📝', hasVal(exp.missions) ? nl2br(exp.missions) : null, true),
                    fieldRow('Outils', '🔧', chips(exp.outils) || null, true),
                    fieldRow('Tuteur / Contact', '🤝', hasVal(exp.tuteur_nom) ? esc(exp.tuteur_nom) : null),
                    fieldRow('Contacts & Liens', '🔗', linksHTML || null, true),
                ].filter(Boolean).join('');

            })
            .catch(err => {
                console.error("Erreur Fetch:", err);
                $('#heroTitle').textContent = 'Erreur';
                $('#heroSub').textContent = 'Impossible de charger les détails.';
                $('#expBody').innerHTML = `<div class="alert error"><code>${esc(String(err.message || err))}</code></div>`;
                $('#commentsSection').style.display = 'none';
            });
    })();
</script>

</body>

</html>