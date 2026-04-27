<?php
/**
 * Simple .env parser to load configuration securely.
 */

// If we already loaded environments, skip.
if (defined('ENV_LOADED')) {
    return;
}

// Charge l'autoloader pour les classes dans src/ (Models, Services)
$autoloadFile = __DIR__ . '/src/autoload.php';
if (file_exists($autoloadFile)) {
    require_once $autoloadFile;
}

$envPath = __DIR__ . '/.env';

if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0)
            continue;

        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $key = trim($parts[0]);
            $value = trim($parts[1]);

            // Remove surrounding quotes if any
            if (preg_match('/^"(.*)"$/', $value, $matches) || preg_match("/^'(.*)'$/", $value, $matches)) {
                $value = $matches[1];
            }

            if (!array_key_exists($key, $_ENV)) {
                putenv(sprintf('%s=%s', $key, $value));
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
}

// Set a flag to avoid multiple parsing
define('ENV_LOADED', true);

// Utility to fetch ENV variable with a fallback
if (!function_exists('env')) {
    function env($key, $default = null)
    {
        $value = getenv($key);
        if ($value === false) {
            return $_ENV[$key] ?? $default;
        }
        return $value;
    }
}
