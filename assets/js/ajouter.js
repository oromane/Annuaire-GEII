/**
 * assets/js/ajouter.js
 * Logique du formulaire d'ajout d'expérience
 */

const API_URL = '../api/experiences.php';
const $ = (s) => document.querySelector(s);
const $$ = (s) => document.querySelectorAll(s);
const flash = $('#flash');
const form = $('#form');

/**
 * Affiche un message flash en haut du formulaire
 */
function msg(html, ok = false) {
    // Utilise le toast global (window.showToast) défini dans app.js
    if (window.showToast) {
        window.showToast(html, ok ? 'success' : 'error');
    }
    flash.innerHTML = '';
}

/**
 * Efface tous les messages d'erreur des champs
 */
function clearErrors() {
    $$('.error-text').forEach(el => el.textContent = '');
    $$('[required]').forEach(el => {
        el.style.borderColor = '';
        el.removeAttribute('aria-invalid'); // A11y
    });
}

/**
 * Affiche les erreurs sous les champs correspondants
 * @param {object} errors - ex: {etudiant_nom: "Nom requis"}
 */
function showFieldErrors(errors) {
    clearErrors();
    for (const [key, message] of Object.entries(errors)) {
        const errorEl = $(`[data-for="${key}"]`);
        const inputEl = $(`#${key}`);
        if (errorEl) {
            errorEl.textContent = message;
        }
        if (inputEl) {
            inputEl.style.borderColor = '#D9534F';
            inputEl.setAttribute('aria-invalid', 'true'); // A11y
        }
    }
}

/**
 * Auto-remplissage de l'année
 */
(function () {
    try {
        const inp = document.getElementById('annee_univ');
        if (inp && !inp.value) {
            const now = new Date();
            const y = now.getMonth() >= 7 ? now.getFullYear() : now.getFullYear() - 1;
            const v = `${y}-${y + 1}`;
            inp.value = v;
        }
    } catch (e) { }
})();

/**
 * Parseur d'année (ex: "2024-2025" ou "2025-2027")
 * Accepte les formats AAAA-AAAA avec une durée de 1 ou 2 ans.
 */
function parseAnneeUniversitaire(str) {
    // Utilise un regex pour capturer les deux années (tolère divers tirets et espaces)
    const m = String(str || '').trim().match(/^(\d{4})\s*[\-\u2013]\s*(\d{4})$/);

    if (!m) return null;

    const a1 = parseInt(m[1], 10); // Année de début (ex: 2025)
    const a2 = parseInt(m[2], 10); // Année de fin (ex: 2027)

    // Condition CLÉ : La durée doit être de 1 ou 2 ans (BUT GEII : 3 ans max)
    const duree = a2 - a1;

    if (duree < 1 || duree > 2) {
        // Rejette si la durée est 0, négative, ou plus que 2 ans (pour rester réaliste pour un BUT)
        return null;
    }

    // Le format est valide, on retourne l'année de début
    return { start: a1, end: a2 };
}

if (form) {
    /**
     * Logique de soumission du formulaire
     */
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        flash.innerHTML = '';
        clearErrors();

        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.textContent = 'Enregistrement...';

        // Affiche le loader global si disponible
        if (window.showLoader) window.showLoader();

        const fd = new FormData(form);

        // Champs requis pour la validation côté client
        const required = {
            'etudiant_nom': 'Nom étudiant requis',
            'etudiant_prenom': 'Prénom étudiant requis',
            'entreprise_nom': 'Entreprise requise',
            'type': 'Type requis',
            'poste': 'Poste requis',
            'annee_univ': 'Année universitaire requise'
        };

        const clientErrors = {};

        // 1. Validation des champs requis
        for (const [key, message] of Object.entries(required)) {
            if (!fd.get(key) || fd.get(key).toString().trim() === '') {
                clientErrors[key] = message;
            }
        }

        // 2. Validation du format d'année
        const anneeValue = fd.get('annee_univ')?.toString() || '';
        if (anneeValue.trim() !== '' && !parseAnneeUniversitaire(anneeValue)) {
            clientErrors['annee_univ'] = 'Format invalide (ex: 2024-2025)';
        } else if (anneeValue.trim() !== '') {
            // C'est valide, on ajoute 'annee' (ex: 2024) au FormData
            fd.set('annee', String(parseAnneeUniversitaire(anneeValue).start));
        }

        // Affiche les erreurs collectées
        if (Object.keys(clientErrors).length > 0) {
            msg(`<strong>Veuillez corriger les champs en rouge.</strong>`, false);
            showFieldErrors(clientErrors);
            submitButton.disabled = false;
            submitButton.textContent = "Enregistrer l'expérience";
            if (window.hideLoader) window.hideLoader();
            return;
        }

        // 3. Envoi API
        try {
            let shouldRedirect = false; // Drapeau pour la redirection
            const r = await fetch(API_URL, { method: 'POST', body: fd, headers: { 'Accept': 'application/json' } });
            const txt = await r.text();
            const clean = txt.replace(/^\uFEFF/, '').trim();
            let j;
            try { j = JSON.parse(clean); } catch { throw new Error(`Réponse API invalide (erreur PHP ?): <pre>${clean || 'Réponse vide.'}</pre>`); }

            if (!r.ok || j.error) {
                // Si l'erreur de l'API est un objet (validation détaillée), on l'affiche
                if (typeof j.error === 'object') {
                    msg(`<strong>Veuillez corriger les champs suivants :</strong>`, false);
                    showFieldErrors(j.error);
                } else {
                    throw new Error(j?.error || `Erreur HTTP ${r.status}`);
                }
            } else {
                // Succès
                shouldRedirect = true; // Marque le succès pour le bloc finally
                msg(j.message || '✅ Expérience soumise ! Merci, votre demande sera traitée rapidement.', true);
                form.reset(); // Vide le formulaire

                // Redirection immédiate vers la page d'accueil
                window.location.href = 'index.php';
            }

        } catch (err) {
            msg('❌ Échec de l’enregistrement : ' + String(err.message || err), false);
            console.error(err);
        } finally {
            if (window.hideLoader) window.hideLoader();
            // Réactive le bouton uniquement si la redirection n'a PAS été lancée
            if (!shouldRedirect) {
                submitButton.disabled = false;
                submitButton.textContent = "Enregistrer l'expérience";
            }
        }
    });
}

// Logique pour les boutons de fichiers (INCHANGÉE)
document.addEventListener("DOMContentLoaded", () => {
    // ... (Logique pour le remplissage auto de l'année)
    try {
        const inp = document.getElementById('annee_univ');
        if (inp && !inp.value) {
            const now = new Date();
            const y = now.getMonth() >= 7 ? now.getFullYear() : now.getFullYear() - 1;
            const v = `${y}-${y + 1}`;
            inp.value = v;
        }
    } catch (e) { }

    // Logique pour les boutons de fichiers (inchangée)
    document.querySelectorAll(".doc-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            document.getElementById(btn.dataset.for)?.click();
        });
    });

    document.querySelectorAll('input[type="file"]').forEach(inp => {
        inp.addEventListener("change", () => {
            const file = inp.files[0];
            const nameEl = document.querySelector(`[data-name="${inp.id}"]`);
            const clearBtn = document.querySelector(`[data-clear="${inp.id}"]`);
            if (file && nameEl && clearBtn) {
                nameEl.textContent = file.name;
                nameEl.classList.remove('is-empty');
                clearBtn.hidden = false;
            } else if (nameEl && clearBtn) {
                nameEl.textContent = "Aucun fichier";
                nameEl.classList.add('is-empty');
                clearBtn.hidden = true;
            }
        });
    });

    document.querySelectorAll(".doc-clear").forEach(btn => {
        btn.addEventListener("click", () => {
            const id = btn.dataset.clear;
            const input = document.getElementById(id);
            const nameEl = document.querySelector(`[data-name="${id}"]`);
            if (input && nameEl) {
                btn.hidden = true;
                nameEl.textContent = "Aucun fichier";
                nameEl.classList.add('is-empty');
                input.value = "";
            }
        });
    });

    // --- Validation visuelle en temps réel ---
    // Pour chaque champ requis, on ajoute une bordure verte/rouge en temps réel
    document.querySelectorAll('#form [required]').forEach(input => {
        const validate = () => {
            const val = input.value.trim();
            if (input.id === 'annee_univ') {
                // Validation spéciale pour l'année
                const valid = parseAnneeUniversitaire(val);
                if (!val) {
                    input.classList.remove('is-valid', 'is-invalid');
                } else if (valid) {
                    input.classList.add('is-valid');
                    input.classList.remove('is-invalid');
                } else {
                    input.classList.add('is-invalid');
                    input.classList.remove('is-valid');
                }
            } else {
                if (!val) {
                    input.classList.remove('is-valid', 'is-invalid');
                } else {
                    input.classList.add('is-valid');
                    input.classList.remove('is-invalid');
                }
            }
        };
        input.addEventListener('input', validate);
        input.addEventListener('blur', validate);
        // Initial check for pre-filled fields
        if (input.value.trim()) validate();
    });
});
