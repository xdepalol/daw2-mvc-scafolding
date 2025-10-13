<?php
// Detecta entorn (p.ex., via variable d’entorn o host)
$host = $_SERVER['HTTP_HOST'] ?? 'cli';  // si és una execució CLI, evita error
if (in_array($host, ['localhost', '127.0.0.1'])) {
    $env = 'local';
} else {
    $env = getenv('APP_ENV') ?: 'production';
}

if ($env === 'local') {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
}

// Handler d’errors i excepcions “bonic”
set_error_handler(function ($severity, $message, $file, $line) use ($env) {
    if (!(error_reporting() & $severity)) return;
    throw new ErrorException($message, 0, $severity, $file, $line);
});

set_exception_handler(function ($e) use ($env) {
    http_response_code(500);
    if ($env === 'local') {
        // Sortida detallada en dev
        echo "<h1>Exception</h1>";
        echo "<pre>", htmlspecialchars((string)$e), "</pre>";
    } else {
        // Missatge genèric en prod + log
        error_log($e);
        echo "Something went wrong.";
    }
});

// Fatal errors (shutdown handler)
register_shutdown_function(function () use ($env) {
    $err = error_get_last();
    if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        http_response_code(500);
        if ($env === 'local') {
            echo "<h1>Fatal error</h1><pre>";
            echo htmlspecialchars("{$err['message']} in {$err['file']}:{$err['line']}");
            echo "</pre>";
        } else {
            error_log(print_r($err, true));
            echo "Something went wrong.";
        }
    }
});
