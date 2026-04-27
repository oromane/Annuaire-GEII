<?php
/**
 * cleanup.php - Auto-nettoyage des expériences supprimées
 * Supprime définitivement les expériences marquées comme supprimées depuis plus d'1 heure
 */

declare(strict_types=1);

/**
 * Nettoie automatiquement les expériences supprimées depuis plus d'1h
 * @param PDO $pdo Instance PDO
 * @return int Nombre d'expériences supprimées définitivement
 */
function cleanup_deleted_experiences(PDO $pdo): int
{
    try {
        $stmt = $pdo->prepare("
            DELETE FROM experiences 
            WHERE deleted_at IS NOT NULL 
            AND deleted_at < NOW() - INTERVAL 1 HOUR
        ");
        $stmt->execute();
        $count = $stmt->rowCount();

        if ($count > 0) {
            log_action('CLEANUP', "$count expérience(s) supprimée(s) définitivement (1h écoulée).");
        }

        return $count;
    } catch (PDOException $e) {
        log_action('ERROR', "Échec du cleanup automatique: " . $e->getMessage());
        return 0;
    }
}
