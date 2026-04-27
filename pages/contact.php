<?php
// contact.php - Version finale avec pièce jointe 5Mo et limite 5000 char
declare(strict_types=1);

// --- SESSION : doit être en tout premier, avant tout output ou require ---
session_start();

// --- CHARGEMENT DE PHPMailer ET DE LA CONFIGURATION ---

// On charge l'autoloader de Composer
require dirname(__DIR__) . '/vendor/autoload.php';

// On charge notre configuration sécurisée (config-mail.php)
require dirname(__DIR__) . '/config-mail.php';

// CSRF token
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'];

// BASE pour les liens absolus
$BASE = '/Annuaire';

$success = false;
$errors = [];
$name = $email = $subject = $message = ''; // Initialize variables
$type = 'Demande générale'; // Default type

// --- Définition des limites ---
$maxFileSize = 5 * 1024 * 1024; // 5 Mo en octets
$maxMessageLength = 5000;      // 5000 caractères

// Gestion du POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérif CSRF
    if (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'])) {
        $errors[] = "Session expirée. Merci de recharger la page.";
    }
    // Honeypot simple
    if (!empty($_POST['website'])) {
        $errors[] = "Échec de validation.";
    }

    // Get form data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $type = trim($_POST['type'] ?? 'Demande générale');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validation
    if ($name === '')
        $errors[] = "Le nom est requis.";
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = "Email invalide.";
    if ($subject === '')
        $errors[] = "L'objet est requis.";
    if ($message === '')
        $errors[] = "Le message est requis.";

    // --- NOUVELLE VALIDATION (Taille message + Fichier) ---

    // 1. Vérifier la longueur du message
    if (mb_strlen($message) > $maxMessageLength) {
        $errors[] = "Le message est trop long ($maxMessageLength caractères maximum).";
    }

    // 2. Vérifier le fichier uploadé (s'il y en a un)
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] !== UPLOAD_ERR_NO_FILE) {

        $fileError = $_FILES['attachment']['error'];

        if ($fileError === UPLOAD_ERR_OK) {
            // Vérifier la taille
            if ($_FILES['attachment']['size'] > $maxFileSize) {
                $errors[] = "Le fichier joint est trop volumineux (5 Mo maximum).";
            }

            // Vérifier le type de fichier (sécurité)
            $allowedTypes = [
                'image/jpeg',
                'image/png',
                'application/pdf',
                'application/msword', // .doc
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document' // .docx
            ];
            // Utilise le type MIME pour plus de sécurité que l'extension
            $fileMimeType = mime_content_type($_FILES['attachment']['tmp_name']);

            if (!in_array($fileMimeType, $allowedTypes)) {
                $errors[] = "Type de fichier non autorisé. (PDF, Word, Images JPG/PNG uniquement)";
            }

        } else if ($fileError === UPLOAD_ERR_INI_SIZE || $fileError === UPLOAD_ERR_FORM_SIZE) {
            // Cette erreur s'affiche si le fichier dépasse la limite de php.ini
            $errors[] = "Le fichier est trop volumineux (limite du serveur atteinte).";
        } else {
            // Autres erreurs (disque plein, etc.)
            $errors[] = "Une erreur est survenue lors de l'envoi du fichier (Code: $fileError).";
        }
    }
    // --- FIN NOUVELLE VALIDATION ---


    if (!$errors) {
        // --- 2. SAUVEGARDE EN BASE DE DONNÉES (Inchangé) ---
        try {
            require_once dirname(__DIR__) . '/api/connexion.php';
            $pdo = function_exists('get_pdo') ? get_pdo() : (isset($pdo) ? $pdo : null);
            if ($pdo instanceof PDO) {
                $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message, type, created_at, ip) VALUES (:name, :email, :subject, :message, :type, NOW(), :ip)");
                $stmt->execute([':name' => $name, ':email' => $email, ':subject' => $subject, ':message' => $message, ':type' => $type, ':ip' => $_SERVER['REMOTE_ADDR'] ?? null]);
            }
        } catch (Throwable $e) { /* non bloquant, on continue vers l'email */
        }

        // --- 3. ENVOI D'EMAIL AVEC MailService ---
        require_once dirname(__DIR__) . '/api/MailService.php';

        try {
            $mailer = new MailService();
            // Prepare attachment if exists
            $attach = (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) ? $_FILES['attachment'] : null;
            $fullMessage = "Type : $type\n\nmessage :\n$message";

            $mailer->sendContactEmail($name, $email, $subject, $fullMessage, $attach);

            // --- Success ---
            $success = true;
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
            $csrf = $_SESSION['csrf'];
            $name = $email = $subject = $message = '';
            $type = 'Demande générale';

        } catch (Exception $e) {
            $errors[] = "Erreur technique lors de l'envoi : " . $e->getMessage();
        }
    }
}

// ==================================================================
// STRUCTURE DE LA PAGE
// ==================================================================

$page_title = 'Contact - Annuaire GEII';
$page_description = 'Contacte l\'équipe de l\'annuaire GEII pour poser une question, signaler un problème ou proposer une amélioration.';
require dirname(__DIR__) . '/partials/header.php';
?>
<main class="main-contact">
    <section class="contact-hero">
        <h1>Contacte-nous ✌️</h1>
        <p>Une question sur le fonctionnement de l'annuaire ? Tu as repéré un bug ou tu as une idée pour améliorer le
            site ?
            N'hésite pas, on est là pour ça ! Ce site est fait par des étudiant.e.s, alors ton avis compte vraiment.</p>
    </section>

    <section class="card contact-card">
        <div class="card-header">
            <h2 class="title">Envoie-nous un message</h2>
            <p class="subtitle">Remplis les champs ci-dessous (ceux marqués <span class="req">*</span> sont
                obligatoires) et on s'occupe du reste !</p>
        </div>

        <div class="card-body">
            <?php if ($success): ?>
                <div class="alert success" role="status">Merci ! Ton message a bien été envoyé, on te répondra dès que
                    possible. 🙏</div>
                <script>document.addEventListener("DOMContentLoaded", () => { window.showToast?.("Merci ! Ton message a bien été envoyé. 🙏", "success"); });</script>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert error" role="alert">
                    <strong>Veuillez corriger :</strong>
                    <ul>
                        <?php foreach ($errors as $e): ?>
                            <li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" class="contact-form" novalidate enctype="multipart/form-data">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
                <input type="text" name="website" tabindex="-1" autocomplete="off"
                    style="position:absolute;left:-10000px;opacity:0" aria-hidden="true">

                <div class="form-grid contact-form-grid">

                    <div class="field">
                        <label for="name">Nom <span class="req">*</span></label>
                        <input id="name" name="name" type="text" required placeholder="NOM Prénom"
                            value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>">
                    </div>

                    <div class="field">
                        <label for="email">Email <span class="req">*</span></label>
                        <input id="email" name="email" type="email" required placeholder="prenom.nom.etu@univ-lille.fr"
                            value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>">
                        <div class="help">Nous n’utilisons ton email que pour te répondre.</div>
                    </div>

                    <div class="field">
                        <label for="type">Type</label>
                        <select id="type" name="type">
                            <?php
                            $types = ['Demande générale', 'Problème technique', 'Donnée à corriger', 'Proposition d’amélioration'];
                            foreach ($types as $t) {
                                $selected = ($type === $t) ? 'selected' : '';
                                echo "<option $selected>" . htmlspecialchars($t, ENT_QUOTES, 'UTF-8') . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="field">
                        <label for="subject">Objet <span class="req">*</span></label>
                        <input id="subject" name="subject" type="text" required placeholder="Sujet de ta demande"
                            value="<?= htmlspecialchars($subject, ENT_QUOTES, 'UTF-8') ?>">
                    </div>

                    <div class="field contact-field-full">
                        <label for="message">Message <span class="req">*</span></label>
                        <textarea id="message" name="message" rows="6" required
                            placeholder="Explique-nous en quelques lignes…"
                            maxlength="5000"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></textarea>
                        <div class="help"><span id="msgCount">0</span> / 5000 caractères</div>
                    </div>

                    <div class="field contact-field-full">
                        <label class="doc-label">Pièce jointe (Optionnel)</label>
                        <div id="dropZone" class="upload-dropzone" aria-label="Zone de dépôt de la pièce jointe">
                            <i class="upload-icon">📁</i>
                            <div class="upload-text">Glissez &amp; déposez votre fichier ici<br>ou</div>
                            <button type="button" class="btn outline doc-btn" data-for="attachment">Choisir un
                                fichier</button>
                            <input id="attachment" name="attachment" type="file"
                                accept=".pdf,.png,.jpg,.jpeg,.doc,.docx" hidden>
                            <div class="upload-filename"><span class="doc-filename is-empty"
                                    data-name="attachment">Aucun fichier sélectionné</span></div>
                        </div>
                        <div class="help" style="margin-top: 0.5rem;">Fichiers autorisés : PDF, Image, Word. Taille max
                            : 5 Mo.</div>
                    </div>

                </div>
                <div class="actions contact-form-actions">
                    <a class="btn outline" href="<?= $BASE ?>/index.php">Retour</a>
                    <button class="btn primary" type="submit">Envoyer</button>
                </div>
            </form>
        </div>
    </section>
</main>

<script>
    // Compteur de caractères du message
    (function () {
        const ta = document.getElementById('message');
        const cnt = document.getElementById('msgCount');
        if (!ta || !cnt) return;

        const update = () => {
            const len = (ta.value || '').length;
            cnt.textContent = len;
            // Optionnel : changer la couleur si on s'approche de la limite
            if (len >= 4950) {
                cnt.style.color = 'red';
            } else {
                cnt.style.color = ''; // revient à la couleur par défaut
            }
        };
        ta.addEventListener('input', update);
        update();
    })();

    // --- NOUVEAU BLOC pour les boutons de fichier (adapté de ajouter.php) ---
    document.addEventListener("DOMContentLoaded", () => {

        // 1. Logique pour le faux bouton "Choisir un fichier"
        document.querySelectorAll(".doc-btn").forEach(btn => {
            btn.addEventListener("click", () => {
                const inputId = btn.dataset.for;
                if (inputId) {
                    document.getElementById(inputId)?.click();
                }
            });
        });

        // 2. Logique pour l'input caché (mettre à jour le texte du span)
        document.querySelectorAll('input[type="file"][name="attachment"]').forEach(inp => {
            inp.addEventListener("change", () => {
                const file = inp.files[0];
                const nameEl = document.querySelector(`[data-name="${inp.id}"]`);

                if (file && nameEl) {
                    nameEl.textContent = file.name; // Affiche le nom du fichier
                    nameEl.classList.remove('is-empty');
                } else if (nameEl) {
                    nameEl.textContent = "Aucun fichier sélectionné";
                    nameEl.classList.add('is-empty');
                }
            });
        });

        // 3. Logique Drag & Drop
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('attachment');
        if (dropZone && fileInput) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, e => {
                    e.preventDefault();
                    e.stopPropagation();
                }, false);
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => dropZone.classList.add('drag-active'), false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => dropZone.classList.remove('drag-active'), false);
            });

            dropZone.addEventListener('drop', e => {
                const dt = e.dataTransfer;
                if (dt.files && dt.files.length) {
                    fileInput.files = dt.files;
                    fileInput.dispatchEvent(new Event('change'));
                }
            }, false);
        }
    });
    // 3. Empêcher le double-clic sur le formulaire de contact
    const contactForm = document.querySelector('.contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            // Si le formulaire est invalide (HTML5), on laisse le navigateur gérer
            if (!this.checkValidity()) return;

            const btn = this.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="loader-spinner small"></span> Envoi...';
            }
        });
    }
</script>

<?php
// 5. Inclure le footer (qui contient <footer>, </body>, </html>)
include dirname(__DIR__) . '/partials/footer.php';
?>