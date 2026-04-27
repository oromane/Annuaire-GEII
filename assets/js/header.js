/*
 * assets/js/header.js
 * Gère le menu mobile (burger) et le changement de thème.
 */
document.addEventListener('DOMContentLoaded', () => {

    /* ==================================================================
       1. GESTION DU MENU MOBILE (BURGER)
       ================================================================== */
    const burgerButton = document.getElementById('hdrBurger');
    const mobileMenu = document.getElementById('hdrMobile');

    if (burgerButton && mobileMenu) {
        burgerButton.addEventListener('click', (e) => {
            e.stopPropagation(); // Évite de fermer immédiatement si on clique sur le burger
            toggleMenu();
        });

        // Fermer le menu si on clique en dehors (sur le backdrop)
        document.addEventListener('click', (e) => {
            if (document.body.classList.contains('mobile-menu-open')) {
                // Si on clique en dehors du menu et du burger
                if (!mobileMenu.contains(e.target) && !burgerButton.contains(e.target)) {
                    closeMenu();
                }
            }
        });

        // Fermer avec Echap
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && document.body.classList.contains('mobile-menu-open')) {
                closeMenu();
            }
        });

        function toggleMenu() {
            const isOpen = mobileMenu.classList.contains('is-open');
            if (isOpen) closeMenu();
            else openMenu();
        }

        function openMenu() {
            burgerButton.classList.add('is-open');
            mobileMenu.classList.add('is-open');
            document.body.classList.add('mobile-menu-open'); // Active le backdrop css

            burgerButton.setAttribute('aria-expanded', 'true');
            mobileMenu.setAttribute('aria-hidden', 'false');
        }

        function closeMenu() {
            burgerButton.classList.remove('is-open');
            mobileMenu.classList.remove('is-open');
            document.body.classList.remove('mobile-menu-open');

            burgerButton.setAttribute('aria-expanded', 'false');
            mobileMenu.setAttribute('aria-hidden', 'true');
        }

        // Fermer le menu si on clique sur un lien (feedback visuel immédiat)
        const menuLinks = mobileMenu.querySelectorAll('.m-link');
        menuLinks.forEach(link => {
            link.addEventListener('click', () => {
                closeMenu();
            });
        });
    }

    /* ==================================================================
       2. GESTION DE L'INTERRUPTEUR DE THÈME
       ================================================================== */
    const themeSwitch = document.getElementById('themeSwitch');

    if (themeSwitch) {
        themeSwitch.addEventListener('change', (e) => {
            let newTheme;

            if (e.target.checked) {
                // Thème sombre
                newTheme = 'dark';
            } else {
                // Thème clair
                newTheme = 'light';
            }

            // 1. Applique le thème instantanément à la page
            document.documentElement.setAttribute('data-theme', newTheme);

            // 2. Sauvegarde le choix dans un cookie pour la prochaine visite
            // Le cookie expire dans 1 an (365 jours)
            // 'path=/' assure que le cookie est valide pour tout le site
            document.cookie = `theme=${newTheme}; max-age=31536000; path=/; SameSite=Lax`;
        });
    }

    /* ==================================================================
       3. GESTION DE L'AUTO-COMPLÉTION DE RECHERCHE (MIS À JOUR)
       ================================================================== */

    const searchInput = document.getElementById('hdrSearchInput');
    const resultsContainer = document.getElementById('hdrSearchResults');
    const searchForm = document.getElementById('hdrSearchForm');

    let debounceTimer;
    const queryCache = {}; // Cache pour les recherches précédentes

    if (searchInput && resultsContainer && searchForm) {

        let currentFocus = -1; // Index de l'élément sélectionné au clavier

        // --- Fonctions Utilitaires ---

        // Met en gras la partie matchant la query
        function highlightMatch(text, query) {
            if (!query) return text;
            const regex = new RegExp(`(${query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
            return text.replace(regex, '<strong>$1</strong>');
        }

        // Gère la classe "active" pour la navigation clavier
        function addActive(items) {
            if (!items) return false;
            removeActive(items);
            if (currentFocus >= items.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = (items.length - 1);

            items[currentFocus].classList.add('active');
            // Scroll si nécessaire
            items[currentFocus].scrollIntoView({ block: 'nearest' });
        }

        function removeActive(items) {
            for (let i = 0; i < items.length; i++) {
                items[i].classList.remove('active');
            }
        }

        // --- 1. Écouter la frappe de l'utilisateur ---
        searchInput.addEventListener('input', () => {
            const query = searchInput.value.trim();
            currentFocus = -1; // Reset de la navigation

            clearTimeout(debounceTimer);

            if (query.length < 2) {
                resultsContainer.innerHTML = '';
                resultsContainer.classList.remove('is-open');
                searchInput.classList.remove('suggestions-open');
                return;
            }

            debounceTimer = setTimeout(() => {
                fetchSuggestions(query);
            }, 300);
        });

        // --- 2. Navigation Clavier (Arrow Down/Up) ---
        searchInput.addEventListener('keydown', (e) => {
            let items = resultsContainer.querySelectorAll('.search-result-item');
            if (items.length > 0) {
                if (e.key === 'ArrowDown') {
                    currentFocus++;
                    addActive(items);
                    e.preventDefault(); // Empêche le curseur de bouger
                } else if (e.key === 'ArrowUp') {
                    currentFocus--;
                    addActive(items);
                    e.preventDefault();
                } else if (e.key === 'Enter') {
                    // Si un item est sélectionné (active), on simule le clic
                    if (currentFocus > -1) {
                        if (items[currentFocus]) {
                            e.preventDefault();
                            items[currentFocus].click();
                        }
                    }
                }
            }
        });

        // --- 3. Appeler l'API et afficher les résultats ---
        async function fetchSuggestions(query) {
            try {
                const lowerQuery = query.toLowerCase();

                // Vérifier si la requête est en cache
                if (queryCache[lowerQuery]) {
                    renderSuggestions(queryCache[lowerQuery], query);
                    return;
                }

                searchInput.classList.add('is-loading');

                const apiBase = window.APP_BASE || '';
                const response = await fetch(`${apiBase}/api/search_global.php?q=${encodeURIComponent(query)}`);

                searchInput.classList.remove('is-loading');

                if (!response.ok) throw new Error('Erreur API');

                const suggestions = await response.json();

                // Mettre en cache les résultats
                queryCache[lowerQuery] = suggestions;

                renderSuggestions(suggestions, query);

            } catch (error) {
                console.error("Erreur search:", error);
                searchInput.classList.remove('is-loading');
            }
        }

        // --- Fonction séparée pour l'affichage ---
        function renderSuggestions(suggestions, query) {
            if (suggestions.length > 0) {
                const html = suggestions.map(item => {
                    // Surlignage du titre
                    const highlightedLabel = highlightMatch(item.label, query);

                    return `
                        <a href="${item.url}" class="search-result-item global-result">
                            <span class="res-icon">${item.icon}</span>
                            <div class="res-content">
                                <div class="res-title">${highlightedLabel}</div>
                                <div class="res-sub">${item.sub}</div>
                            </div>
                            <span class="res-type">${item.type}</span>
                        </a>
                    `;
                }).join('');

                resultsContainer.innerHTML = html;
                resultsContainer.classList.add('is-open');
                searchInput.classList.add('suggestions-open');
            } else {
                // Message "Aucun résultat" élégant
                resultsContainer.innerHTML = `
                    <div class="search-no-result">
                        Aucun résultat pour "<strong>${query}</strong>" 
                    </div>`;
                resultsContainer.classList.add('is-open');
                searchInput.classList.add('suggestions-open');
            }
        }

        // --- 4. Cacher les résultats si on clique ailleurs ---
        document.addEventListener('click', (e) => {
            if (!searchForm.contains(e.target)) {
                resultsContainer.classList.remove('is-open');
                searchInput.classList.remove('suggestions-open');
            }
        });

        // --- 5. Si on re-clique dans la barre, on ré-affiche ---
        searchInput.addEventListener('focus', () => {
            if (resultsContainer.innerHTML !== '') {
                resultsContainer.classList.add('is-open');
                searchInput.classList.add('suggestions-open');
            }
        });

        // --- 6. Gérer "Entrée" global (Submit standard) ---
        searchForm.addEventListener('submit', (e) => {
            // Si aucune sélection clavier n'est faite mais qu'il y a un premier résultat
            if (currentFocus === -1 && resultsContainer.classList.contains('is-open')) {
                const firstLink = resultsContainer.querySelector('.search-result-item');
                if (firstLink && firstLink.href) {
                    e.preventDefault();
                    window.location.href = firstLink.href;
                }
            }
            // Sinon, comportement par défaut (recherche Annuaire classique)
        });

        // --- 7. Raccourcis Clavier Global (/) ---
        document.addEventListener('keydown', (e) => {
            if (e.key === '/' && document.activeElement !== searchInput) {
                e.preventDefault();
                searchInput.focus();
            }
        });
    }
    /* ==================================================================
       4. SCROLL TO TOP BUTTON
       ================================================================== */
    const scrollToTopBtn = document.getElementById('scrollToTop');

    if (scrollToTopBtn) {
        // Afficher/Masquer le bouton au scroll
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                scrollToTopBtn.classList.add('is-visible');
            } else {
                scrollToTopBtn.classList.remove('is-visible');
            }
        });

        // Remonter en haut au clic
        scrollToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

});
