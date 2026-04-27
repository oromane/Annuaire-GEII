<?php
// src/Services/AuthService.php — Gestion centralisée de l'authentification admin
declare(strict_types=1);

class AuthService
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Démarre la session de manière sécurisée
     */
    public function startSecureSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'cookie_httponly' => true,
                'cookie_samesite' => 'Strict',
                'use_strict_mode' => true,
            ]);
        }
    }

    /**
     * Vérifie les identifiants et connecte l'admin
     */
    public function login(string $username, string $password): bool
    {
        $stmt = $this->pdo->prepare("SELECT id, username, password FROM admins WHERE username = :u LIMIT 1");
        $stmt->execute([':u' => $username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_login_time'] = time();

            // Met à jour last_login
            $this->pdo->prepare("UPDATE admins SET last_login = NOW() WHERE id = :id")
                ->execute([':id' => $admin['id']]);

            return true;
        }

        return false;
    }

    /**
     * Vérifie si l'admin est connecté (avec expiration de session)
     */
    public function isLoggedIn(int $maxLifetime = 3600): bool
    {
        if (empty($_SESSION['admin_id']))
            return false;

        $loginTime = $_SESSION['admin_login_time'] ?? 0;
        if (time() - $loginTime > $maxLifetime) {
            $this->logout();
            return false;
        }

        return true;
    }

    /**
     * Déconnexion complète
     */
    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
    }

    /**
     * Redirige vers login si non connecté
     */
    public function requireLogin(string $loginUrl = 'login.php'): void
    {
        if (!$this->isLoggedIn()) {
            header('Location: ' . $loginUrl);
            exit;
        }
    }
}
