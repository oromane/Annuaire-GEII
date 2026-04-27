<?php
declare(strict_types=1);

// 1. Charger le bootstrap pour accéder à la session et aux helpers
// C'est ce fichier qui fait le session_start()
require __DIR__ . '/_bootstrap.php';

// 2. (Optionnel) Logguer l'action avant de détruire la session
if (function_exists('log_action') && is_logged()) {
    log_action('AUTH', "Déconnexion admin réussie pour: " . $_SESSION['admin_username']);
}

// 3. Vider le tableau $_SESSION
$_SESSION = [];

// 4. Détruire la session côté serveur
session_destroy();

// 5. (Optionnel mais recommandé) Effacer le cookie de session côté client
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 6. Rediriger vers la page d'accueil
// ../ signifie "remonter d'un dossier" (sortir de /admin/ pour aller à la racine)
header("Location: ../index.php");
exit;
?>