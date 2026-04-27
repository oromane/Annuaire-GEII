<?php
// admin/_bootstrap.php - session, sécurité, helpers
declare(strict_types=1);

// 1. Démarrage et configuration de la session
if (session_status() === PHP_SESSION_NONE) {
    $timeout_duration = 900;
    ini_set('session.gc_maxlifetime', (string) $timeout_duration);
    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params([
        'lifetime' => $timeout_duration,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
}

// 2. Connexion BDD
require_once dirname(__DIR__) . '/api/connexion.php';
$pdo = $pdo ?? (function_exists('get_pdo') ? get_pdo() : null);
if (!$pdo) {
    http_response_code(500);
    exit('Erreur critique: Impossible de se connecter à la base de données.');
}

// 3. Constantes
define('SESSION_TIMEOUT', 900);
// 4. Fonctions d'authentification
function is_logged(): bool
{
    return !empty($_SESSION['admin_id']);
}

/**
 * Vérifie si un admin est connecté ET si sa session n'a pas expiré.
 * À appeler au début de chaque page admin (sauf login.php).
 */
function require_login(): void
{
    if (!is_logged()) {
        flash_message('Vous devez être connecté pour accéder à cette page.', 'error');
        redirect('login.php');
    }

    // Vérification de l'expiration de session
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
        // La session a expiré
        session_unset();
        session_destroy();
        flash_message('Votre session a expiré pour inactivité.', 'error');
        redirect('login.php');
    }
    // Mettre à jour l'heure de la dernière activité
    $_SESSION['last_activity'] = time();
}

// 5. Fonctions de sécurité (CSRF)
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) { // Utilise 'csrf'
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function check_csrf(string $t): bool
{
    return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $t); // Utilise 'csrf'
}

/**
 * Vérifie le token CSRF depuis POST/GET. Si invalide, stoppe et redirige.
 * @param string $redirect_url URL où rediriger en cas d'échec.
 */
function verify_csrf(string $redirect_url = 'index.php'): void
{
    // Lit 'csrf' (sans underscore) depuis POST ou GET
    $token = $_POST['csrf'] ?? $_GET['csrf'] ?? '';

    if (!check_csrf($token)) {
        log_action('SECURITY', 'Échec de la validation CSRF.');
        flash_message('Erreur de sécurité. Votre session a peut-être expiré. Veuillez réessayer.', 'error');
        redirect($redirect_url);
    }
    // L'action est valide, on régénère le token pour la prochaine action
    unset($_SESSION['csrf']); // Utilise 'csrf'
}


// 6. Helpers (Logs, Flash, HTML)

/**
 * Écrit un message dans le fichier de log.
 * @param string $level (ex: AUTH, ADMIN, ERROR)
 * @param string $message Le message à enregistrer
 */
function log_action(string $level, string $message): void
{
    global $pdo;
    try {
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $admin_id = $_SESSION['admin_id'] ?? null;

        $logMessage = "[$level] " . trim($message);

        if ($pdo) {
            $stmt = $pdo->prepare("INSERT INTO logs_audit (action, details, admin_id, ip_address) VALUES (:act, :det, :admin, :ip)");
            $stmt->execute([
                ':act' => $level,
                ':det' => $logMessage,
                ':admin' => $admin_id,
                ':ip' => $ip
            ]);
        }
    } catch (Throwable $e) {
        error_log("Impossible d'écrire dans la base de données logs_audit: " . $e->getMessage());
    }
}

function e($v)
{
    return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): void
{
    header("Location: " . $url);
    exit;
}

function flash_message(string $message, string $type = 'success'): void
{
    $_SESSION['flash_message'] = ['message' => $message, 'type' => $type];
}

function display_flash_message(): void
{
    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        echo '<div class="flash-message ' . e($flash['type']) . '">' . e($flash['message']) . '</div>';
        unset($_SESSION['flash_message']);
    }
}
?>