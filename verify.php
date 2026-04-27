<?php
// verify.php - Système de vérification d'email
require __DIR__ . '/api/connexion.php';

$page_title = 'Vérification Email - Annuaire GEII';
require __DIR__ . '/partials/header.php';

$token = $_GET['token'] ?? '';
$success = false;
$message = 'Le lien de vérification est invalide ou a expiré.';

if (!empty($token)) {
    try {
        // Recherche de l'expérience avec ce token
        $stmt = $pdo->prepare("SELECT id, email_verified_at FROM experiences WHERE email_verification_token = :token");
        $stmt->execute([':token' => $token]);
        $row = $stmt->fetch();

        if ($row) {
            if ($row['email_verified_at'] !== null) {
                // Déjà vérifié
                $success = true;
                $message = 'Votre adresse email a déjà été vérifiée avec succès. Attendez que l\'administrateur approuve votre expérience.';
            } else {
                // On met à jour
                $update = $pdo->prepare("UPDATE experiences SET email_verified_at = NOW(), email_verification_token = NULL WHERE id = :id");
                $update->execute([':id' => $row['id']]);

                $success = true;
                $message = 'Merci ! Votre adresse email a été vérifiée avec succès. L\'administrateur va maintenant valider votre dépôt.';
            }
        }
    } catch (Throwable $e) {
        $message = 'Une erreur technique est survenue. Veuillez réessayer plus tard.';
        // $message .= ' ' . $e->getMessage();
    }
}
?>

<div class="header-spacer"></div>

<main class="container" style="max-width: 600px; margin: 4rem auto; text-align: center;">
    <div class="card" style="padding: 2rem;">
        <h2 style="margin-bottom: 1rem;">Vérification Email</h2>

        <?php if ($success): ?>
            <div
                style="background-color: #d1fae5; color: #065f46; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <strong>✔️ Confirmé !</strong><br><br>
                <?= htmlspecialchars($message) ?>
            </div>
            <p>Vous pouvez retourner à l'accueil ou découvrir les expériences déjà partagées.</p>
        <?php else: ?>
            <div
                style="background-color: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <strong>❌ Erreur</strong><br><br>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div style="margin-top: 2rem;">
            <a href="index.php" class="btn outline primary">Retour à l'accueil</a>
            <a href="pages/annuaire.php" class="btn primary">Voir l'annuaire</a>
        </div>
    </div>
</main>

<?php require __DIR__ . '/partials/footer.php'; ?>