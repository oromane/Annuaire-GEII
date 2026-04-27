/*
 * Fichier : assets/js/app.js
 * (Doit être chargé sur toutes les pages, par ex. dans le footer)
 */

/**
 * Affiche un message "Toast" en bas à droite.
 * @param {string} message Le texte HTML à afficher.
 * @param {string} type 'success', 'error', ou 'info' (par défaut)
 * @param {number} duration Durée en ms avant disparition (défaut: 4000)
 */
window.showToast = function (message, type = 'info', duration = 4000) {
    const container = document.getElementById('toast-container');
    if (!container) {
        console.error('Div #toast-container introuvable dans le DOM.');
        return;
    }

    // Créer l'élément toast
    const toast = document.createElement('div');
    toast.className = `toast ${type}`; // 'info' n'a pas de style, 'success' et 'error' oui
    toast.innerHTML = message;

    // Ajouter au DOM
    container.appendChild(toast);

    // Animer l'apparition
    setTimeout(() => {
        toast.classList.add('show');
    }, 100); // Léger délai pour que la transition CSS s'applique

    // Préparer la disparition
    setTimeout(() => {
        toast.classList.remove('show');

        // Attendre la fin de l'animation de sortie avant de supprimer du DOM
        toast.addEventListener('transitionend', () => {
            if (toast.parentElement) {
                toast.parentElement.removeChild(toast);
            }
        }, { once: true });

    }, duration);
}

/**
 * Affiche un loader global (spinner) dans un conteneur donné ou en plein écran (overlay).
 * @param {HTMLElement|string} target L'élément cible (ou son ID) où afficher le loader. Si null, affiche un overlay.
 */
window.showLoader = function (target = null) {
    let container = target;
    if (typeof target === 'string') {
        container = document.getElementById(target);
    }

    const loaderHtml = '<div class="loader-spinner"></div>';

    if (container) {
        // Sauvegarde le contenu actuel si besoin (optionnel, ici on remplace ou on ajoute)
        // Pour simplifier : on ajoute une classe 'is-loading' et on injecte le spinner
        container.classList.add('is-loading');
        // On vérifie s'il y a déjà un loader
        if (!container.querySelector('.loader-spinner')) {
            container.insertAdjacentHTML('beforeend', loaderHtml);
        }
    } else {
        // Overlay plein écran (à créer si inexistant)
        let overlay = document.getElementById('global-loader-overlay');
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.id = 'global-loader-overlay';
            overlay.className = 'global-loader-overlay';
            overlay.innerHTML = loaderHtml;
            document.body.appendChild(overlay);
        }
        overlay.style.display = 'flex';
    }
}

/**
 * Masque le loader.
 * @param {HTMLElement|string} target L'élément cible. Si null, masque l'overlay.
 */
window.hideLoader = function (target = null) {
    let container = target;
    if (typeof target === 'string') {
        container = document.getElementById(target);
    }

    if (container) {
        container.classList.remove('is-loading');
        const spinner = container.querySelector('.loader-spinner');
        if (spinner) {
            spinner.remove();
        }
    } else {
        const overlay = document.getElementById('global-loader-overlay');
        if (overlay) {
            overlay.style.display = 'none';
        }
    }
}

    /**
     * Back to Top Button Functionality
     * Shows/hides the scroll-to-top button and handles scroll-to-top action
     */
    (function () {
        const scrollToTopBtn = document.getElementById('scrollToTop');

        if (!scrollToTopBtn) return; // Exit if button doesn't exist

        // Show/hide button based on scroll position
        function toggleScrollToTopBtn() {
            if (window.scrollY > 300) {
                scrollToTopBtn.classList.add('show');
            } else {
                scrollToTopBtn.classList.remove('show');
            }
        }

        // Scroll to top smoothly
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Event listeners
        window.addEventListener('scroll', toggleScrollToTopBtn);
        scrollToTopBtn.addEventListener('click', scrollToTop);

        // Initial check
        toggleScrollToTopBtn();
    })();

/**
 * Scroll Reveal Animations (Intersection Observer)
 * Observes elements with .animate-on-scroll and adds .visible when in viewport
 */
document.addEventListener('DOMContentLoaded', () => {
    // Check if user prefers reduced motion
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (prefersReducedMotion) {
        // If they prefer reduced motion, make everything visible immediately
        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            el.classList.add('visible');
        });
        return;
    }

    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1 // Trigger when 10% of the element is visible
    };

    const scrollObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                // Optional: Stop observing once it's visible (animate only once)
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        scrollObserver.observe(el);
    });
});