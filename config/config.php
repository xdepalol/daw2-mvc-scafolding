<?php
// Carrega .env (o env local PHP)
require __DIR__ . '/env.php'; // defineix APP_ENV, DB_*, APP_DEBUG...

// Errors per entorn
if (!headers_sent()) {
    ini_set('display_errors', APP_DEBUG ? '1' : '0');
    ini_set('display_startup_errors', APP_DEBUG ? '1' : '0');
    error_reporting(APP_DEBUG ? E_ALL : E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
}

// Connexió (mysqli) centralitzada
final class Database {
    public static function connect(): mysqli {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT ?? 3306);
        if ($conn->connect_error) {
            throw new RuntimeException('DB connect error: ' . $conn->connect_error);
        }
        $conn->set_charset('utf8mb4');
        return $conn;
    }
}
