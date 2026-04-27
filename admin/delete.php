<?php
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php'; // Inclut PDO, session, et tous les helpers
require_login(); // Vérifie le login ET l'expiration de session

// --- 1. Vérification de la méthode ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    log_action('SECURITY', 'Tentative de suppression via GET bloquée.'); // Log de sécurité

    if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Méthode non autorisée. POST requis.']);
    } else {
        echo 'Méthode non autorisée.';
    }
    exit;
}

// --- 2. Récupération et validation des données ---
$id = (int) ($_POST['id'] ?? 0);
$csrf = (string) ($_POST['csrf'] ?? '');

// --- 3. Vérification de sécurité (ID et CSRF) ---
// Utilise la fonction check_csrf() de _bootstrap.php
if ($id <= 0 || !check_csrf($csrf)) {
    http_response_code(400);
    $errorMessage = 'Requête invalide ou session expirée.';

    flash_message($errorMessage, 'error');
    log_action('SECURITY', "Échec de suppression (ID: $id, CSRF invalide)."); // Log de sécurité

    if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
        header('Content-Type: application/json');
        echo json_encode(['error' => $errorMessage]);
    } else {
        redirect('index.php');
    }
    exit;
}

// --- 4. Logique de suppression (SOFT DELETE) ---
try {
    // Soft delete: marque comme supprimé au lieu de supprimer définitivement
    $stmt = $pdo->prepare("UPDATE experiences SET deleted_at = NOW() WHERE id = :id AND deleted_at IS NULL");
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() > 0) {
        $successMessage = 'Expérience déplacée vers la corbeille. Restauration possible pendant 1h.';
        flash_message($successMessage, 'success');

        // --- AJOUT AU LOG ---
        log_action('SOFT_DELETE', "Expérience (ID: $id) marquée comme supprimée.");

        if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => $successMessage]);
            exit;
        }
    } else {
        $errorMessage = 'Aucune expérience trouvée avec l\'ID ' . $id . ' ou déjà supprimée.';
        flash_message($errorMessage, 'error');

        if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => $errorMessage]);
            exit;
        }
    }

} catch (PDOException $e) {
    // --- 5. Gestion des erreurs BDD ---
    $httpCode = 500;
    $errorMessage = 'Erreur serveur lors de la suppression.';

    if ($e->getCode() === '23000') {
        $httpCode = 409;
        $errorMessage = 'Erreur : Impossible de supprimer cette expérience (contrainte de clé étrangère).';
    }

    // --- AJOUT AU LOG ---
    log_action('ERROR', "Échec suppression BDD (ID: $id): " . $e->getMessage());

    flash_message($errorMessage, 'error');

    if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
        http_response_code($httpCode);
        header('Content-Type: application/json');
        echo json_encode(['error' => $errorMessage]);
        exit;
    }
}

// Redirection par défaut si ce n'était pas une requête fetch
redirect('index.php');
?>