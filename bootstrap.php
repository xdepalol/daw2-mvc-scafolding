<?php
// bootstrap.php

// index.php és a /var/www/html/daw2-2526/index.php
define('BASE_PATH', __DIR__);                     // /var/www/html/daw2-2526
define('VIEW_PATH', BASE_PATH . '/view');         // /var/www/html/daw2-2526/view

// Mostra errors en entorn local
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Sessió
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Connexió BD i helpers
require_once __DIR__ . '/env.php';
require_once __DIR__ . '/database/database.php';
require_once __DIR__ . '/view/core-view.php';

// Helpers generals
function redirect(string $url): void {
    header("Location: $url");
    exit;
}

function flash(string $key, string $message): void {
    $_SESSION['flash'][$key] = $message;
}

function get_flash(string $key): ?string {
    if (!isset($_SESSION['flash'][$key])) return null;
    $msg = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);
    return $msg;
}

// Autoload molt simple
spl_autoload_register(function ($class) {
    $prefix = "App\\";
    $baseDir = __DIR__ . "/";

    // només classes del nostre namespace
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative = strtolower(substr($class, $len));

    $file = $baseDir . str_replace('\\', '/', $relative) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// asset_url
function asset_url(string $path): string {
    // $path és "css/app.css", "js/app.js", ...
    $url = rtrim(BASE_URI, '/') . '/assets/' . ltrim($path, '/');
    $fs  = __DIR__ . '/assets/' . ltrim($path, '/'); // camí en disc

    // afegeix ?v=timestamp per busting de caché si existeix físicament
    if (is_file($fs)) {
        $url .= '?v=' . filemtime($fs);
    }
    return $url;
}