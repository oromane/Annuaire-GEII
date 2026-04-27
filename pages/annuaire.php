<?php
// annuaire.php - Liste des expériences (recherche + filtres + pagination)
// Définir le titre de la page pour le header
$page_title = 'Annuaire des expériences GEII';
$page_description = 'Recherche parmi les expériences de stage et d\'alternance des étudiant.e.s GEII. Filtre par ville, domaine, type et rayon kilométrique.';
require dirname(__DIR__) . '/partials/header.php';
?>
<div class="header-spacer"></div>

<main class="main-annuaire">
    <section class="annuaire-hero">
        <div class="hero-content">
            <h1>Annuaire des expériences GEII</h1>
            <p class="subtitle">Ici, on partage nos expériences de stage et d'alternance entre étudiant.e.s GEII.
                Une entreprise qui a accueilli un.e stagiaire peut aussi prendre un.e alternant.e, et inversement !
                Utilise les filtres ci-dessous pour trouver ce qui t'intéresse.</p>
            <a class="btn primary" href="ajouter.php">Ajouter mon expérience</a>
        </div>
    </section>

    <section class="contact-card annuaire-filters">
        <div class="header">
            <h2 class="title">Rechercher</h2>
        </div>
        <div class="contact-form">
            <div class="form-grid annuaire-grid-filters" role="search" aria-label="Filtres de recherche">
                <div class="field annuaire-q-field"> <label for="q">Mot-clé</label> <input id="q" type="text"
                        placeholder="poste, entreprise, techno…" aria-label="Rechercher par mot-clé"> </div>
                <div class="field"> <label for="type">Type</label> <select id="type" aria-label="Filtrer par type">
                        <option value="">Tous</option>
                        <option>Stage</option>
                        <option>Alternance</option>
                    </select> </div>
                <div class="field"> <label for="domaine">Domaine</label> <select id="domaine"
                        aria-label="Filtrer par domaine"></select> </div>
                <div class="field field-ville-distance" id="distanceField">
                    <label for="ville">📍 Ville</label>
                    <input id="ville" type="text" list="villesList" placeholder="Ex: Lille, Ronchin, Arras…"
                        autocomplete="off" aria-label="Filtrer par ville">
                    <datalist id="villesList"></datalist>

                    <div class="rayon-row" id="rayonRow">
                        <label class="rayon-label" for="distanceRange">
                            🔍 Rayon :
                            <span id="distanceLabelValue" class="rayon-value-label">Toute la France</span>
                        </label>
                        <input id="distanceRange" type="range" min="0" max="5" step="1" value="0" disabled
                            aria-label="Rayon de recherche en kilomètres" class="rayon-slider">
                        <div class="rayon-hint" id="rayonHint">💡 Saisissez une ville pour activer le rayon</div>
                    </div>
                </div>
                <div class="field"> <label for="annee">Année</label> <select id="annee"
                        aria-label="Filtrer par année"></select> </div>
            </div><!-- end .form-grid -->
            <div class="actions annuaire-filter-actions"
                style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.25rem;">
                <button id="btnReset" class="btn outline" aria-label="Réinitialiser les filtres">Réinitialiser</button>
                <button id="btnSearch" class="btn primary" aria-label="Lancer la recherche">Rechercher</button>
            </div>
        </div><!-- end .contact-form -->
    </section>
    <section class="contact-card annuaire-results">
        <div class="header">
            <h2 class="title">Résultats</h2>
            <p class="subtitle" id="resultsMeta">-</p>
        </div>
        <div class="contact-form">
            <div id="cards" class="annuaire-grid-results"></div>
            <div class="actions annuaire-pagination">
                <button id="prev" class="btn outline" disabled>← Précédent</button>
                <button id="next" class="btn primary" disabled>Suivant →</button>
            </div>
        </div>
    </section>

    <section class="companies-head-section" id="companiesHeadSection" style="display:none; margin-top: 48px;">
        <div class="card card--static companies-header-card">
            <div class="card-body" style="text-align: center; padding: 24px 22px;">
                <h2 class="title">Entreprises ayant accueilli des GEII</h2>
                <p class="subtitle">Liste des entreprises contactables via site, LinkedIn ou contact fourni par les
                    étudiant.e.s.</p>
            </div>
        </div>
    </section>

    <section class="companies-table-section" id="companiesTableSection" style="display:none; margin-top: 24px;">
        <div class="card card--static companies-table-card">
            <div class="company-grid" id="companyContacts">
            </div>
        </div>
    </section>

    <section class="companies-footer-section" id="companiesFooterSection" style="display:none; margin-top: 24px;">
        <div class="card card--static companies-footer-card">
            <div class="card-body" style="text-align: center;">
                <p class="help" style="margin-bottom: 20px;">
                    Cette liste inclut des entreprises n'ayant pas encore accueilli de GEII, mais jugées pertinentes
                    pour un contact.
                </p>
                <div style="margin-bottom: 8px;">
                    <a href="entreprises.php" class="btn outline primary">Voir la liste complète</a>
                </div>
            </div>
        </div>
        <hr style="border: none; border-top: 1px solid var(--card-border); margin: 40px 0;">
    </section>
</main>

<?php require dirname(__DIR__) . '/partials/footer.php'; ?>

<script>
    (function () {
        const $ = s => document.querySelector(s);
        const fQ = $('#q'), fType = $('#type'), fDomaine = $('#domaine'), fVille = $('#ville'), fAnnee = $('#annee');
        const fDistanceRange = $('#distanceRange'), distanceLabel = $('#distanceLabel'), distanceField = $('#distanceField');
        const btnSearch = $('#btnSearch'), btnReset = $('#btnReset');
        const listEl = $('#cards'), metaEl = $('#resultsMeta'), prevBtn = $('#prev'), nextBtn = $('#next');

        const companiesHeadEl = $('#companiesHeadSection');
        const companiesTableEl = $('#companiesTableSection');
        const companiesFooterEl = $('#companiesFooterSection');
        const companyContactsEl = $('#companyContacts');
        const resultsSectionEl = document.querySelector('.annuaire-results');
        const listVilles = $('#villesList');

        const API_BASE_URL = '../api/annuaire.php';

        let page = 1, lastTotal = 0;
        const pageSize = 20;

        // ========= DISTANCE FILTER =========
        const DISTANCE_STEPS = [0, 10, 20, 50, 100, 200]; // 0 = no filter
        const DISTANCE_LABELS = ['Toute la France', '10 km', '20 km', '50 km', '100 km', '200 km'];
        const geoCache = {}; // cache: { 'lille': {lat,lng}, ... }
        let refCoords = null; // lat/lng of the reference city

        // Haversine distance (km)
        function haversine(lat1, lon1, lat2, lon2) {
            const R = 6371;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) ** 2 + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dLon / 2) ** 2;
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        }

        // Geocode a city via api-adresse.data.gouv.fr with localStorage caching
        async function geocode(city) {
            const key = city.toLowerCase().trim();

            // Check in-memory cache first
            if (geoCache[key]) return geoCache[key];

            // Check localStorage for persistent cache
            const localStorageKey = `geo_${key}`;
            const cached = localStorage.getItem(localStorageKey);
            if (cached) {
                try {
                    const coords = JSON.parse(cached);
                    geoCache[key] = coords;
                    return coords;
                } catch (e) {
                    console.warn('localStorage parsing error:', e);
                }
            }

            try {
                const r = await fetch(`https://api-adresse.data.gouv.fr/search?q=${encodeURIComponent(city)}&type=municipality&limit=1`);
                const j = await r.json();
                if (j.features && j.features.length > 0) {
                    const [lng, lat] = j.features[0].geometry.coordinates;
                    const coords = { lat, lng };

                    // Cache in both memory and localStorage
                    geoCache[key] = coords;
                    try {
                        localStorage.setItem(localStorageKey, JSON.stringify(coords));
                    } catch (e) {
                        console.warn('localStorage quota exceeded:', e);
                    }

                    return coords;
                }
            } catch (e) {
                console.warn('Geocoding failed:', e);
            }
            return null;
        }

        // Slider update
        function updateDistanceLabel() {
            const idx = parseInt(fDistanceRange.value);
            if (document.getElementById('distanceLabelValue')) {
                document.getElementById('distanceLabelValue').textContent = DISTANCE_LABELS[idx];
            }
        }
        fDistanceRange?.addEventListener('input', updateDistanceLabel);

        // Enable/disable slider based on ville input
        const rayonHint = document.getElementById('rayonHint');
        let geocodeTimeout = null;
        fVille?.addEventListener('input', () => {
            clearTimeout(geocodeTimeout);
            const v = fVille.value.trim();
            if (!v) {
                fDistanceRange.disabled = true;
                fDistanceRange.value = 0;
                refCoords = null;
                updateDistanceLabel();
                if (rayonHint) rayonHint.classList.remove('hidden');
                return;
            }
            // Show geocoding hint while waiting
            if (rayonHint) rayonHint.textContent = '⏳ Recherche de la ville…';
            geocodeTimeout = setTimeout(async () => {
                const coords = await geocode(v);
                if (coords) {
                    refCoords = coords;
                    fDistanceRange.disabled = false;
                    if (rayonHint) { rayonHint.textContent = '✅ Ville trouvée - ajuster le rayon ci-dessus'; rayonHint.classList.add('hidden'); }
                } else {
                    refCoords = null;
                    fDistanceRange.disabled = true;
                    fDistanceRange.value = 0;
                    updateDistanceLabel();
                    if (rayonHint) { rayonHint.textContent = '❌ Ville non reconnue, réessayez'; rayonHint.classList.remove('hidden'); }
                }
            }, 400);
        });

        const esc = s => String(s ?? '').replace(/[&<>"']/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m]));

        let currentSortKey = 'entreprise_nom';
        let sortDirection = 1;

        const normalize = (data) => {
            const companies = data.companies || [];
            if (Array.isArray(data?.items)) return { items: data.items, total: data.total ?? data.items.length, companies };
            if (Array.isArray(data?.data)) return { items: data.data, total: data.total ?? data.data.length, companies };
            if (Array.isArray(data?.results)) return { items: data.results, total: data.total ?? data.results.length, companies };
            if (Array.isArray(data)) return { items: data, total: data.length, companies };
            if (data && typeof data === 'object') return { items: [data], total: 1, companies };
            return { items: [], total: 0, companies: [] };
        };

        // ====================================================================
        // FONCTIONS DE RENDU (Inchangées)
        // ====================================================================
        function createLink(url, type) {
            if (!url) return '';
            if (type === 'email') {
                return `<a class="table-link-email" href="mailto:${url}" title="Envoyer un email">${url}</a>`;
            }
            if (type === 'linkedin') {
                return `<a class="table-link-icon" href="${url}" target="_blank" rel="noopener" title="LinkedIn"></a>`;
            }
            if (type === 'phone') {
                return `<a class="table-link-phone" href="tel:${url}" title="Appeler">${url}</a>`;
            }
            return `<a class="table-link" href="${url}" target="_blank" rel="noopener">${url}</a>`;
        }
        function sortCompanies(companies, key) {
            if (key === currentSortKey) {
                sortDirection *= -1;
            } else {
                currentSortKey = key;
                sortDirection = 1;
            }
            companies.sort((a, b) => {
                const aValue = String(a[key] || '').toLowerCase();
                const bValue = String(b[key] || '').toLowerCase();
                if (aValue < bValue) return -1 * sortDirection;
                if (aValue > bValue) return 1 * sortDirection;
                return 0;
            });
            renderCompaniesTable(companies);
        }
        function addSortingListeners(originalCompanies) {
            const headers = document.querySelectorAll('.companies-table th');
            headers.forEach(h => {
                h.classList.remove('sorted-asc', 'sorted-desc');
                if (h.dataset.sort) {
                    h.classList.add('sortable');
                }
                if (h.dataset.sort === currentSortKey) {
                    h.classList.add(sortDirection === 1 ? 'sorted-asc' : 'sorted-desc');
                }
            });
            headers.forEach(header => {
                if (header.dataset.sort) {
                    header.addEventListener('click', () => {
                        const sortKey = header.dataset.sort;
                        sortCompanies(originalCompanies, sortKey);
                    });
                }
            });
        }
        function renderCompaniesTable(companies) {
            const table = document.createElement('table');
            table.className = 'companies-table annuaire-sample-table';
            table.innerHTML = `
            <thead>
                <tr>
                    <th data-sort="entreprise_nom">Nom de l'entreprise</th>
                    <th data-sort="ville">Ville</th>
                    <th data-sort="domaine">Domaine</th>
                    <th data-sort="entreprise_site">Site Entreprise</th> <th data-sort="entreprise_phone">Téléphone</th> 
                    <th data-sort="entreprise_email">Contact Email</th>
                </tr>
            </thead>
            <tbody>
                ${companies.map(comp => {
                const phone = comp.entreprise_phone;
                const email = comp.entreprise_email;
                return `
                    <tr>
                        <td data-label="Nom">${comp.entreprise_nom}</td>
                        <td data-label="Ville">${comp.ville || comp.entreprise_adresse || '-'}</td>
                        <td data-label="Domaine">${comp.domaine || '-'}</td>
                        <td data-label="Site Entreprise">${comp.entreprise_site ? createLink(comp.entreprise_site, 'site') : '-'}</td> <td data-label="Téléphone">${phone ? createLink(phone, 'phone') : '-'}</td>
                        <td data-label="Email">${email ? createLink(email, 'email') : '-'}</td>
                    </tr>
                `}).join('')}
            </tbody>
        `;
            companyContactsEl.innerHTML = '';
            companyContactsEl.appendChild(table);
            addSortingListeners(companies);
        }

        async function loadFilters() {
            try {
                const r = await fetch(`${API_BASE_URL}?meta=true`, { headers: { 'Accept': 'application/json' } });
                if (!r.ok) throw new Error('Erreur API Meta');
                const meta = await r.json();

                fillSelectOnce(fDomaine, meta.domaines, 'Tous les domaines');
                fillDatalist(listVilles, meta.villes);
                fillSelectOnce(fAnnee, meta.annees, 'Toutes les années');
            } catch (e) {
                console.error("Erreur chargement filtres:", e);
                // Optionnel : afficher une petite alerte si les filtres ne chargent pas
            }
        }

        function fillSelectOnce(selectEl, values, placeholder = '') {
            // Garde la valeur actuelle pour la restaurer
            const currentValue = selectEl.value;

            // Remplit uniquement si la liste est vide (sauf pour le placeholder)
            if (!selectEl || selectEl.options.length > 1) return;

            const opts = Array.from(new Set(values.filter(Boolean))).sort((a, b) => String(a).localeCompare(String(b), 'fr'));
            selectEl.innerHTML = `<option value="">${placeholder || 'Tous'}</option>` +
                opts.map(v => `<option value="${esc(v)}">${esc(v)}</option>`).join('');

            // Restaure la valeur si elle existait (ex: après un reset)
            selectEl.value = currentValue;
        }

        function fillDatalist(datalistEl, values) {
            if (!datalistEl || datalistEl.options.length > 0) return;
            const opts = Array.from(new Set(values.filter(Boolean))).sort((a, b) => String(a).localeCompare(String(b), 'fr'));
            datalistEl.innerHTML = opts.map(v => `<option value="${esc(v)}">`).join('');
        }

        async function fetchWithFallback(params) {
            const qs = new URLSearchParams(params).toString();
            try {
                const r = await fetch(`${API_BASE_URL}?${qs}`, { headers: { 'Accept': 'application/json' } });
                if (!r.ok) {
                    const errorText = await r.text();
                    throw new Error(`API a retourné un statut ${r.status}. Réponse: ${errorText}`);
                }
                const j = await r.json();
                if (j && !j.error) { return normalize(j); }
                throw new Error(j.error || 'Erreur inconnue dans la réponse JSON.');
            } catch (e) {
                throw e;
            }
        }

        // ================= Rendu carte (mode recherche) =================
        function renderCard(exp) {
            const typeBadge = `<span class="xp-card__badge badge ${exp.type === 'Alternance' ? 'badge-alternance' : 'badge-stage'}">${esc(exp.type)}</span>`;
            const contactIcons = [];
            if (exp.etudiant_linkedin) {
                contactIcons.push(`<a href="${exp.etudiant_linkedin}" target="_blank" title="LinkedIn Étudiant"><i class="fa fa-linkedin"></i></a>`);
            }
            if (exp.etudiant_email) {
                contactIcons.push(`<a href="mailto:${exp.etudiant_email}" title="Contacter l'Étudiant"><i class="fa fa-envelope"></i></a>`);
            }
            const iconsHtml = contactIcons.length > 0 ? `<div class="xp-card__icons">${contactIcons.join(' ')}</div>` : '';

            return `
            <article class="xp-card card animate-on-scroll">
                <header class="xp-card__head">
                    <div class="xp-card__title">
                        <span class="company">${esc(exp.entreprise_nom || 'Entreprise ?')}</span>
                        <span class="job-title">${esc(exp.poste || 'Poste ?')}</span>
                    </div>
                    ${exp.type ? typeBadge : ''}
                </header>
                <div class="xp-card__body">
                    <a class="btn btn--ghost" href="experiences.php?id=${encodeURIComponent(exp.id)}">Voir plus</a>
                    ${iconsHtml}
                </div>
            </article>
        `;
        }

        function renderSkeletons() {
            let html = '';
            for(let i=0; i<6; i++) {
                html += `
                <article class="card xp-card skeleton-card">
                    <header class="xp-card__header">
                        <div class="xp-card__title">
                            <div class="skeleton skeleton-title"></div>
                            <div class="skeleton skeleton-company"></div>
                        </div>
                    </header>
                    <div class="xp-card__body">
                        <div style="width:100%;">
                            <div class="skeleton skeleton-text"></div>
                            <div class="skeleton skeleton-text short"></div>
                        </div>
                        <div style="margin-top: 1rem;">
                            <div class="skeleton skeleton-tag"></div>
                            <div class="skeleton skeleton-tag" style="width: 60px;"></div>
                        </div>
                    </div>
                </article>
                `;
            }
            return html;
        }

        async function load() {
            metaEl.textContent = 'Chargement…';
            listEl.innerHTML = renderSkeletons();

            const distIdx = parseInt(fDistanceRange?.value || 0);
            const distKm = DISTANCE_STEPS[distIdx] || 0;
            const useDistance = distKm > 0 && refCoords;

            // If distance filter is active, do NOT send ville to API (we filter client-side)
            const params = {
                page, page_size: useDistance ? 200 : pageSize,
                q: fQ?.value?.trim() || '',
                type: fType?.value || '',
                domaine: fDomaine?.value || '',
                ville: useDistance ? '' : (fVille?.value || ''),
                annee: fAnnee?.value || ''
            };

            const filtersActive = Object.values(params).some((val, key) => key > 1 && val !== '');
            const isFilteredSearch = filtersActive || params.q || useDistance;

            try {
                const data = await fetchWithFallback(params);

                companiesHeadEl.style.display = 'none';
                companiesTableEl.style.display = 'none';
                companiesFooterEl.style.display = 'none';
                resultsSectionEl.style.display = 'block';

                let items = data.items || [];

                // --- CLIENT-SIDE DISTANCE FILTER ---
                if (useDistance && items.length > 0) {
                    // Geocode each experience's city and filter by distance
                    const geoPromises = items.map(async (item) => {
                        if (!item.ville) return { item, dist: Infinity };
                        const coords = await geocode(item.ville);
                        if (!coords) return { item, dist: Infinity };
                        const dist = haversine(refCoords.lat, refCoords.lng, coords.lat, coords.lng);
                        return { item, dist };
                    });
                    const results = await Promise.all(geoPromises);
                    items = results
                        .filter(r => r.dist <= distKm)
                        .sort((a, b) => a.dist - b.dist)
                        .map(r => r.item);
                }

                lastTotal = useDistance ? items.length : Number(data.total || 0);

                if (!items.length) {
                    metaEl.textContent = useDistance
                        ? `Aucune exp\xE9rience trouv\xE9e dans un rayon de ${distKm} km.`
                        : 'Aucun r\xE9sultat correspondant \xE0 vos crit\xE8res.';
                    prevBtn.disabled = nextBtn.disabled = true;
                    listEl.innerHTML = '';
                } else {
                    // Client-side pagination for distance results
                    const displayItems = useDistance
                        ? items.slice((page - 1) * pageSize, page * pageSize)
                        : items;
                    const displayTotal = useDistance ? items.length : lastTotal;

                    metaEl.textContent = `${displayTotal} r\xE9sultat${displayTotal > 1 ? 's' : ''} - page ${page}`
                        + (useDistance ? ` (rayon ${distKm} km)` : '');
                    listEl.innerHTML = displayItems.map(renderCard).join('');
                    const lastPage = Math.max(1, Math.ceil(displayTotal / pageSize));
                    prevBtn.disabled = page <= 1;
                    nextBtn.disabled = page >= lastPage;
                }

                // Affichage du tableau d'entreprises (seulement si pas de recherche et page 1)
                if (!isFilteredSearch && page === 1) {
                    // On utilise API_BASE_URL (ex-COMPANIES_ENDPOINT)
                    const r = await fetch(`${API_BASE_URL}?contacts_only=true&limit=20&random=true`, { headers: { 'Accept': 'application/json' } });

                    if (r.ok) {
                        const allCompanies = await r.json();
                        // Assure que allCompanies est un tableau, qu'il vienne de .companies ou soit la racine
                        const companiesList = Array.isArray(allCompanies.companies) ? allCompanies.companies.slice() : (Array.isArray(allCompanies) ? allCompanies.slice() : []);

                        if (companiesList.length > 0) {
                            sortCompanies(companiesList, currentSortKey);
                            companiesHeadEl.style.display = 'block';
                            companiesTableEl.style.display = 'block';
                            companiesFooterEl.style.display = 'block';
                        } else {
                            companiesHeadEl.style.display = 'none';
                            companiesTableEl.style.display = 'none';
                            companiesFooterEl.style.display = 'none';
                        }
                    } else {
                        console.error("Impossible de charger la liste exhaustive des entreprises.");
                        companiesHeadEl.style.display = 'none';
                        companiesTableEl.style.display = 'none';
                        companiesFooterEl.style.display = 'none';
                    }
                }

            } catch (e) {
                console.error("Erreur de chargement (Annuaire):", e);
                window.showToast?.(` Erreur API: ${e.message.split(':').shift()}`, 'error');

                metaEl.textContent = 'Erreur lors de la récupération des données.';
                listEl.innerHTML = `<div class="alert error">Erreur lors du chargement des résultats. Vérifiez l'API ou la console (F12).</div>`;

                companiesHeadEl.style.display = 'none';
                companiesTableEl.style.display = 'none';
                companiesFooterEl.style.display = 'none';
                resultsSectionEl.style.display = 'block';
                prevBtn.disabled = nextBtn.disabled = true;
            }
        }

        // Gestionnaires d'événements
        btnSearch?.addEventListener('click', (ev) => { ev.preventDefault?.(); page = 1; load(); window.scrollTo({ top: 0, behavior: 'smooth' }); });
        btnReset?.addEventListener('click', (ev) => {
            ev.preventDefault?.();
            [fQ, fType, fDomaine, fVille, fAnnee].forEach(el => { if (el) el.value = ''; });
            fDistanceRange.value = 0;
            fDistanceRange.disabled = true;
            refCoords = null;
            updateDistanceLabel();
            page = 1;
            load();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        fQ?.addEventListener('keydown', (ev) => { if (ev.key === 'Enter') { ev.preventDefault(); page = 1; load(); window.scrollTo({ top: 0, behavior: 'smooth' }); } });
        prevBtn?.addEventListener('click', () => { if (page > 1) { page--; load(); window.scrollTo({ top: 0, behavior: 'smooth' }); } });
        nextBtn?.addEventListener('click', () => { page++; load(); window.scrollTo({ top: 0, behavior: 'smooth' }); });

        // U2 — Auto-search when a select filter changes (no need to click Rechercher)
        [fType, fDomaine, fAnnee].forEach(sel => {
            sel?.addEventListener('change', () => { page = 1; load(); });
        });

        function checkURLForSearch() {
            const urlParams = new URLSearchParams(window.location.search);
            const queryFromHeader = urlParams.get('q');
            if (queryFromHeader && fQ) {
                fQ.value = queryFromHeader;
            }
        }

        // --- Chargement initial ---
        checkURLForSearch();
        loadFilters(); // Charge les filtres 
        load(); // Charge les résultats
    })();
</script>