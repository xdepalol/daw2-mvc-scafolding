<?php
// view/core-view.php

/**
 * Renderitza una vista dins d'un layout.
 *
 * @param string $view   Ruta relativa a /view (sense .php), ex: 'equipo/index'
 * @param array  $params Variables a exposar a la vista i el layout (ex: ['title'=>'Equips'])
 * @param string $layout Ruta relativa del layout (sense .php), ex: 'layouts/app'
 */
function render(string $view, array $params = [], string $layout = 'layouts/app'): void
{
    // Exposem variables a la vista i al layout
    extract($params, EXTR_SKIP);

    // Localitza els fitxers
    $viewFile   = __DIR__ . "/../../view/{$view}.php";
    $layoutFile = __DIR__ . "/../../view/{$layout}.php";

    if (!is_file($viewFile)) {
        http_response_code(500);
        echo "View not found: {$viewFile}";
        return;
    }
    if (!is_file($layoutFile)) {
        http_response_code(500);
        echo "Layout not found: {$layoutFile}";
        return;
    }

    // El layout farà include del $content
    $content = $viewFile;
    require $layoutFile;
}
