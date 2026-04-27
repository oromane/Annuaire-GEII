<?php
// src/Services/SecurityService.php — Fonctions de sécurité centralisées
declare(strict_types=1);

class SecurityService
{
    /**
     * Génère ou récupère un token CSRF en session
     */
    public static function csrfToken(): string
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf'];
    }

    /**
     * Vérifie un token CSRF soumis
     */
    public static function verifyCsrf(string $submitted): bool
    {
        return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $submitted);
    }

    /**
     * Régénère le token CSRF (après une action réussie)
     */
    public static function regenerateCsrf(): string
    {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
        return $_SESSION['csrf'];
    }

    /**
     * Vérifie le honeypot anti-spam (retourne true si c'est un bot)
     */
    public static function isHoneypotFilled(string $fieldName = 'website'): bool
    {
        return !empty($_POST[$fieldName]);
    }

    /**
     * Hash une IP avec un salt journalier (RGPD compliant)
     */
    public static function hashIp(?string $ip = null): string
    {
        $rawIp = $ip ?? ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
        $dailySalt = date('Y-m-d');
        return hash('sha256', $rawIp . $dailySalt);
    }

    /**
     * Rate limiting basique basé sur IP hashée
     * Retourne true si la limite est dépassée
     */
    public static function isRateLimited(PDO $pdo, string $action, int $maxPerHour = 10): bool
    {
        try {
            $ipHash = self::hashIp();
            $stmt = $pdo->prepare("
                SELECT COUNT(*) FROM logs_audit 
                WHERE ip_address = :ip AND action = :action 
                AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)
            ");
            $stmt->execute([':ip' => $ipHash, ':action' => $action]);
            return (int) $stmt->fetchColumn() >= $maxPerHour;
        } catch (Throwable $e) {
            return false; // En cas d'erreur, on laisse passer
        }
    }

    /**
     * Échappe une chaîne pour l'affichage HTML
     */
    public static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Génère un token de vérification email
     */
    public static function generateEmailToken(): string
    {
        return bin2hex(random_bytes(32));
    }
}
