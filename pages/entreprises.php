<?php
// entreprises.php - Liste exhaustive des entreprises contactables (style tableau)
$page_title = 'Répertoire des entreprises · Annuaire GEII';
$page_description = 'Consultez la liste complète des entreprises qui recrutent des étudiant.e.s GEII en stage ou en alternance. Trouvez les contacts et sites web pour vos candidatures.';
require dirname(__DIR__) . '/partials/header.php';
?>
<div class="header-spacer"></div>

<main class="main-entreprises">
    <section class="page-hero">
        <h1>Répertoire des entreprises</h1>
        <p class="subtitle">Voici la liste complète des entreprises qui ont accueilli ou qui sont susceptibles
            d'accueillir
            des étudiant.e.s GEII. Clique sur les en-têtes de colonnes pour trier, et n'hésite pas à contacter directement
            les entreprises qui t'intéressent !</p>
    </section>

    <section class="card card--static companies-table-card">
        <div class="card-body">
            <div id="tableStatus">Chargement du tableau...</div>

            <div class="table-container" id="companiesTableContainer">
            </div>
        </div>
    </section>
</main>
<script>
    (function () {
        const TABLE_STATUS = document.getElementById('tableStatus');
        const TABLE_CONTAINER = document.getElementById('companiesTableContainer');
        const API_URL = '../api/annuaire.php?contacts_only=true';

        let currentSortKey = 'entreprise_nom';
        let sortDirection = -1;

        /**
         * Crée un lien hypertexte pour les colonnes d'icônes (LinkedIn) ou de texte (Email).
         */
        function createLink(url, type) {
            if (!url) return '';

            let icon = '';
            let label = '';
            let href = url;

            if (type === 'email') {
                // Pour l'email: retourne le texte du mail comme lien cliquable non souligné
                return `<a class="table-link-email" href="mailto:${url}" title="Envoyer un email">${url}</a>`;
            }

            // Pour les autres types (LinkedIn)
            if (type === 'linkedin') {
                icon = '→';
                label = 'LinkedIn';
            }

            return `<a class="table-link-icon" href="${href}" target="_blank" rel="noopener" title="${label}">${icon}</a>`;
        }

        /**
         * Logique de tri: inverse la direction ou change de colonne, puis trie les données.
         */
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

            // Réaffiche le tableau avec les données triées
            renderTable(companies);
        }

        /**
         * Attache les écouteurs de clics aux en-têtes.
         */
        function addSortingListeners(originalCompanies) {
            const headers = document.querySelectorAll('.companies-table th'); // Cible tous les TH

            // Met à jour l'indicateur visuel de tri (classes CSS)
            headers.forEach(h => {
                h.classList.remove('sorted-asc', 'sorted-desc');
                // Ajoute la classe 'sortable' pour activer le style d'icône (si le TH a un attribut data-sort)
                if (h.dataset.sort) {
                    h.classList.add('sortable');
                }

                if (h.dataset.sort === currentSortKey) {
                    h.classList.add(sortDirection === 1 ? 'sorted-asc' : 'sorted-desc');
                }
            });

            // Ajoute l'écouteur de clic
            headers.forEach(header => {
                if (header.dataset.sort) { // N'ajoute l'écouteur que si le TH a une clé de tri
                    header.addEventListener('click', () => {
                        const sortKey = header.dataset.sort;
                        // On trie les données originales stockées en mémoire
                        sortCompanies(originalCompanies, sortKey);
                    });
                }
            });
        }
        /**
         * Génère le tableau HTML à partir des données des entreprises.
         */
        function renderTable(companies) {
            if (!companies || companies.length === 0) {
                TABLE_STATUS.innerHTML = '<div class="alert info">Aucune entreprise contactable trouvée.</div>';
                return;
            }

            TABLE_STATUS.style.display = 'none'; // Cache le message de statut

            const table = document.createElement('table');
            table.className = 'companies-table';

            // Entêtes du tableau (Header) - COLONNE LINKEDIN SUPPRIMÉE
            table.innerHTML = `
            <thead>
                <tr>
                    <th data-sort="entreprise_nom">Nom de l'entreprise</th>
                    <th data-sort="ville">Ville</th>
                    <th data-sort="domaine">Domaine</th>
                    <th data-sort="entreprise_phone">Téléphone</th> 
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
                        <td data-label="Domaine"><div class="text-clamp-2">${comp.domaine || '-'}</div></td>
                        <td data-label="Téléphone">${phone || '-'}</td>
                        <td data-label="Email">${createLink(email, 'email')}</td>
                    </tr>
                `}).join('')}
            </tbody>
        `;

            // Nettoie l'ancien contenu et injecte le tableau
            TABLE_CONTAINER.innerHTML = '';
            TABLE_CONTAINER.appendChild(table);

            // NOUVEAU: Appelle l'écouteur de tri APRÈS que le tableau est dans le DOM
            addSortingListeners(companies);
        }
        function renderTableSkeletons() {
            let html = '<table class="companies-table"><thead><tr><th>Nom</th><th>Ville</th><th>Domaine</th><th>Téléphone</th><th>Contact</th></tr></thead><tbody>';
            for(let i=0; i<8; i++) {
                html += `<tr>
                    <td><div class="skeleton skeleton-text" style="width: 70%; margin:0;"></div></td>
                    <td><div class="skeleton skeleton-text" style="width: 50%; margin:0;"></div></td>
                    <td><div class="skeleton skeleton-text" style="width: 90%; margin:0;"></div></td>
                    <td><div class="skeleton skeleton-text" style="width: 60%; margin:0;"></div></td>
                    <td><div class="skeleton skeleton-text" style="width: 40%; margin:0;"></div></td>
                </tr>`;
            }
            html += '</tbody></table>';
            return html;
        }

        // Fonction de chargement des données (UNIQUE APPEL)
        async function loadCompanies() {
            TABLE_CONTAINER.innerHTML = renderTableSkeletons();
            try {
                const response = await fetch(API_URL, { headers: { 'Accept': 'application/json' } });

                if (!response.ok) {
                    // Si l'erreur persiste, vous verrez le message exact du serveur ici
                    throw new Error(`Erreur HTTP ${response.status} lors de la récupération des données.`);
                }

                const data = await response.json();
                // Copie les données pour que le tri ne modifie pas la source (pour les tris consécutifs)
                const companiesList = data.companies ? data.companies.slice() : (Array.isArray(data) ? data.slice() : []);

                if (companiesList.length === 0) {
                    TABLE_STATUS.innerHTML = '<div class="alert info">Aucune entreprise contactable trouvée.</div>';
                    return;
                }

                // Applique le tri initial par défaut (Nom de l'entreprise)
                sortCompanies(companiesList, currentSortKey);
                // sortCompanies va appeler renderTable() à la fin

            } catch (error) {
                console.error("Erreur de chargement du tableau:", error);
                TABLE_STATUS.innerHTML = `<div class="alert error">Impossible de charger les données : ${error.message}</div>`;
            }
        }

        loadCompanies();
    })();
</script>
<?php require dirname(__DIR__) . '/partials/footer.php'; ?>