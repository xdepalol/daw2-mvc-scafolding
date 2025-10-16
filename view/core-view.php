<?php
function render(string $view, array $params = [], string $layout = 'layouts/app'): void {
    extract($params, EXTR_SKIP);

    $viewFile   = VIEW_PATH . '/' . str_replace('.', '/', $view) . '.php';
    $layoutFile = VIEW_PATH . '/' . $layout . '.php';

    if (!is_file($viewFile)) {
        throw new RuntimeException("View not found: {$viewFile}");
    }
    if (!is_file($layoutFile)) {
        throw new RuntimeException("Layout not found: {$layoutFile}");
    }
    // $viewFile   = __DIR__ . "/{$view}.php";
    // $layoutFile = __DIR__ . "/{$layout}.php";
    // if (!is_file($viewFile) || !is_file($layoutFile)) { http_response_code(500); echo 'View o layout no trobats.'; return; }
    $content = $viewFile;               // el layout farà include_once $content
    $pageScripts = $pageScripts ?? null; // opcional: scripts específics
    require $layoutFile;
}
