<?php
// Fichier : config-mail.php
// Configuration centralisée de l'envoi d'emails.
// Les valeurs sont lues depuis le fichier .env (voir .env.example).

// Inclure le parseur .env 
require_once __DIR__ . '/config.php';

// Mode d'envoi : 'phpmail' (PHP mail() natif) ou 'smtp' (serveur SMTP externe)
define('MAIL_MODE', env('MAIL_MODE', 'phpmail'));

// --- CONFIG SMTP (si MAIL_MODE = 'smtp') ---
define('SMTP_HOST', env('SMTP_HOST', 'localhost'));
define('SMTP_PORT', env('SMTP_PORT', 587));

// Identifiants SMTP
define('SMTP_USER', env('SMTP_USER', ''));
define('SMTP_PASS', env('SMTP_PASS', ''));

// --- CONFIG EXPÉDITEUR / DESTINATAIRE ---
define('FROM_EMAIL', env('FROM_EMAIL', 'noreply@example.com'));

// L'email qui reçoit les messages de contact et notifications
define('ADMIN_EMAIL', env('ADMIN_EMAIL', 'admin@example.com'));