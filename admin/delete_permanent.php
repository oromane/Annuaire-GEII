<?php
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php';
require_login();

// Vérification POST (ID + CSRF)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    log_action('SECURITY', 'Tentative de suppression définitive via GET bloquée.');
    redirect('trash.php');
}

$id = (int) ($_POST['id'] ?? 0);
$csrf = (string) ($_POST['csrf'] ?? '');

if ($id <= 0 || !check_csrf($csrf)) {
    flash_message('Requête invalide ou session expirée.', 'error');
    log_action('SECURITY', "Échec de suppression définitive (ID: $id, CSRF invalide).");
    redirect('trash.php');
}

// Suppression DÉFINITIVE (DELETE FROM)
try {
    // 1. Récupérer l'ID de l'entreprise associée avant la suppression
    $stmtFind = $pdo->prepare("SELECT entreprise_id FROM experiences WHERE id = :id AND deleted_at IS NOT NULL");
    $stmtFind->execute([':id' => $id]);
    $entreprise_id = $stmtFind->fetchColumn();

    // 2. Supprimer l'expérience définitivement
    $stmt = $pdo->prepare("DELETE FROM experiences WHERE id = :id AND deleted_at IS NOT NULL");
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() > 0) {
        // 3. Gestion en cascade : L'entreprise est-elle orpheline ?
        if ($entreprise_id) {
            $stmtCheck = $pdo->prepare("SELECT id FROM experiences WHERE entreprise_id = :ent_id LIMIT 1");
            $stmtCheck->execute([':ent_id' => $entreprise_id]);
            if ($stmtCheck->rowCount() === 0) {
                // Aucun autre étudiant n'a cette entreprise, on la supprime
                $stmtDelEnt = $pdo->prepare("DELETE FROM entreprises WHERE id = :ent_id");
                $stmtDelEnt->execute([':ent_id' => $entreprise_id]);
                log_action('HARD_DELETE', "Entreprise orpheline (ID: $entreprise_id) supprimée en cascade.");
            }
        }

        flash_message('Expérience supprimée définitivement.', 'success');
        log_action('HARD_DELETE', "Expérience (ID: $id) supprimée définitivement depuis la corbeille.");
    } else {
        flash_message('Aucune expérience trouvée dans la corbeille avec cet ID.', 'error');
    }
} catch (PDOException $e) {
    log_action('ERROR', "Échec suppression définitive (ID: $id): " . $e->getMessage());
    flash_message('Erreur serveur lors de la suppression définitive.', 'error');
}

redirect('trash.php');
