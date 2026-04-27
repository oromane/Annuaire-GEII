<?php
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php'; // Charge la session et les helpers

$error = '';

// Si l'utilisateur est déjà connecté, on le redirige vers le dashboard
if (is_logged()) {
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification CSRF sur le login
    $csrfToken = $_POST['csrf'] ?? '';
    if (!check_csrf($csrfToken)) {
        $error = 'Erreur de sécurité. Veuillez réessayer.';
    } else {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $error = 'Tous les champs sont requis.';
        } else {
            try {
                // 1. Récupérer l'utilisateur par son nom
                $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :username");
                $stmt->execute([':username' => $username]);
                $admin = $stmt->fetch();

                // 2. Vérifier le mot de passe
                if ($admin && password_verify($password, $admin['password'])) {

                    // --- CONNEXION RÉUSSIE ---
                    session_regenerate_id(true);

                    // Stocke les infos en session
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_username'] = $admin['username'];
                    // On ne stocke plus de rôle
                    $_SESSION['last_activity'] = time();

                    // Nettoyage des messages flash précédents (ex: "Vous devez être connecté")
                    if (isset($_SESSION['flash_message'])) {
                        unset($_SESSION['flash_message']);
                    }

                    // 3. Log l'action
                    log_action('AUTH', 'Connexion admin réussie.');

                    // 4. Redirection
                    redirect('index.php');

                } else {
                    // Échec
                    log_action('AUTH', "Échec de connexion pour l'utilisateur: $username");
                    $error = "Nom d'utilisateur ou mot de passe incorrect.";
                }
            } catch (Throwable $e) {
                log_action('ERROR', 'Erreur BDD sur login.php: ' . $e->getMessage());
                $error = "Une erreur serveur est survenue.";
            }
        }
    } // fin check_csrf
}
?>
<?php $theme = $_COOKIE['theme'] ?? 'light'; ?>
<!DOCTYPE html>
<html lang="fr" data-theme="<?= htmlspecialchars($theme) ?>">

<head>
    <meta charset="UTF-8">
    <title>Admin - Connexion</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=admin">
</head>

<body>
    <main class="main-login">
        <section class="card login-card">
            <h1>Connexion Admin</h1>

            <?php if ($error): ?>
                <div class="alert error" style="margin-bottom: 20px;">
                    <?= e($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="login-form">
                <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                <div class="field">
                    <label for="username" class="sr-only">Nom d'utilisateur</label>
                    <input id="username" name="username" type="text" placeholder="Nom d'utilisateur" required>
                </div>
                <div class="field">
                    <label for="password" class="sr-only">Mot de passe</label>
                    <input id="password" name="password" type="password" placeholder="Mot de passe" required>
                </div>
                <button type="submit" class="btn primary login-button">Connexion</button>
            </form>
        </section>
    </main>
</body>

</html>