<?php
// src/autoload.php — Simple autoloader pour les classes dans src/
// Charge automatiquement les classes quand elles sont instanciées
spl_autoload_register(function (string $class) {
    // Chemins à scanner (Models puis Services)
    $dirs = [
        __DIR__ . '/Models/',
        __DIR__ . '/Services/',
    ];

    foreach ($dirs as $dir) {
        $file = $dir . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
