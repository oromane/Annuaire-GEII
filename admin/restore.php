<?php
// restore.php — Restaure une expérience depuis la corbeille (POST uniquement)
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php';
require_login();

// SÉCURITÉ : Méthode POST obligatoire
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    log_action('SECURITY', 'Tentative de restauration via GET bloquée.');
    flash_message('Méthode non autorisée.', 'error');
    redirect('trash.php');
}

// Vérification POST (ID + CSRF)
$id = (int) ($_POST['id'] ?? 0);
$csrf = (string) ($_POST['csrf'] ?? '');

if ($id <= 0 || !check_csrf($csrf)) {
    flash_message('Requête invalide ou session expirée.', 'error');
    log_action('SECURITY', "Échec de restauration (ID: $id, CSRF invalide).");
    redirect('trash.php');
}

// Restauration (UPDATE deleted_at = NULL)
try {
    $stmt = $pdo->prepare("UPDATE experiences SET deleted_at = NULL WHERE id = :id AND deleted_at IS NOT NULL");
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() > 0) {
        flash_message('Expérience restaurée avec succès.', 'success');
        log_action('RESTORE', "Expérience (ID: $id) restaurée depuis la corbeille.");
    } else {
        flash_message('Aucune expérience trouvée dans la corbeille avec cet ID.', 'error');
    }
} catch (PDOException $e) {
    log_action('ERROR', "Échec restauration (ID: $id): " . $e->getMessage());
    flash_message('Erreur serveur lors de la restauration.', 'error');
}

redirect('trash.php');
