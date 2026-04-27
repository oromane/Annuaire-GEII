<?php
// ============================================================================
// api/connexion.php - Connexion PDO centralisée (MySQL / XAMPP)
// ============================================================================

// Inclure le parseur .env et gérer les variables d'environnement
require_once __DIR__ . '/../config.php';

// Configuration de connexion récupérée depuis l'environnement
$DB_HOST = env('DB_HOST', 'localhost');
$DB_NAME = env('DB_NAME', 'annuaire_geii');
$DB_USER = env('DB_USER', 'root');
$DB_PASS = env('DB_PASS', '');
$DB_PORT = env('DB_PORT', 3306);
$DB_CHARSET = 'utf8mb4';

//  Options PDO sécurisées
$pdo_options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Lève les erreurs SQL
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Résultats sous forme de tableau associatif
    PDO::ATTR_EMULATE_PREPARES => true,                  // Active l’émulation pour permettre la réutilisation de :q
];

//  Fonction de connexion (appelée depuis d’autres fichiers)
if (!function_exists('get_pdo')) {
    function get_pdo(): PDO
    {
        static $pdo = null;
        if ($pdo === null) {
            global $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS, $DB_PORT, $DB_CHARSET, $pdo_options;
            $dsn = "mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_NAME;charset=$DB_CHARSET";
            try {
                $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $pdo_options);
            } catch (Throwable $e) {
                http_response_code(500);
                header('Content-Type: application/json; charset=utf-8');
                while (ob_get_level())
                    ob_end_clean();
                $errorMsg = mb_convert_encoding($e->getMessage(), 'UTF-8', 'ISO-8859-1');
                echo json_encode([
                    'error' => 'Connexion MySQL impossible',
                    'detail' => $errorMsg,
                ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
                exit;
            }
        }
        return $pdo;
    }
}

//  Crée la connexion par défaut si appelée directement
try {
    $pdo = get_pdo();
} catch (Throwable $e) {
    // On ignore ici : l'erreur est déjà gérée dans get_pdo()
}
