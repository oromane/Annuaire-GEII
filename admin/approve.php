<?php
// approve.php — Approuve une expérience (POST uniquement)
declare(strict_types=1);
require __DIR__ . '/_bootstrap.php';

// 1. SÉCURITÉ : Vérifie le login ET l'expiration de session
require_login();

// 2. SÉCURITÉ : Méthode POST obligatoire
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    log_action('SECURITY', 'Tentative d\'approbation via GET bloquée.');
    flash_message('Méthode non autorisée.', 'error');
    redirect('index.php');
}

$id = (int) ($_POST['id'] ?? 0);

// 3. SÉCURITÉ : Vérifie le token CSRF
if ($id <= 0) {
    flash_message('ID invalide.', 'error');
    redirect('index.php');
}
verify_csrf('index.php');

try {
    $stmt = $pdo->prepare("UPDATE experiences SET is_approved = 1 WHERE id = :id");
    $stmt->execute([':id' => $id]);

    log_action('APPROVE', "Expérience (ID: $id) approuvée.");
    flash_message('Expérience ID ' . $id . ' approuvée avec succès.', 'success');

} catch (Throwable $e) {
    log_action('ERROR', "Échec d'approbation (ID: $id): " . $e->getMessage());
    flash_message('Erreur lors de l\'approbation.', 'error');
}

redirect('index.php');
?>