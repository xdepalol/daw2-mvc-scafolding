<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/controller/equipocontroller.php';

// Obtenim informació bàsica de la petició
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Prefix del projecte (netejem)
$baseUri  = BASE_URI;
if (strpos($uri, $baseUri) === 0) {
    $path = substr($uri, strlen($baseUri));
} else {
    $path = $uri; // per si el projecte es mou
}
$path = '/' . ltrim($path, '/'); // normalitza

// Definició de rutes
$routes = [
    // Llistat
    ['GET', '#^/$#',                    ['App\Controller\EquipoController', 'index']],
    ['GET', '#^/equipo$#',              ['App\Controller\EquipoController', 'index']],
    // Crear
    ['GET', '#^/equipo/create$#',       ['App\Controller\EquipoController', 'create']],
    ['POST', '#^/equipo$#',             ['App\Controller\EquipoController', 'store']],
    // Llegir (show)
    ['GET', '#^/equipo/(\d+)$#',        ['App\Controller\EquipoController', 'show']],
    // Editar
    ['GET', '#^/equipo/(\d+)/edit$#',   ['App\Controller\EquipoController', 'edit']],
    ['POST', '#^/equipo/(\d+)/update$#',['App\Controller\EquipoController', 'update']],
    // Eliminar
    ['POST', '#^/equipo/(\d+)/delete$#',['App\Controller\EquipoController', 'destroy']],
];

// Router
foreach ($routes as [$m, $regex, $handler]) {
    if ($m === $method && preg_match($regex, $path, $matches)) {
        array_shift($matches); // elimina l’element 0 (tota la coincidència)
        $controller = new $handler[0]();
        return call_user_func_array([$controller, $handler[1]], $matches);
    }
}

http_response_code(404);
render('errors/404', ['title' => 'No trobat']);
