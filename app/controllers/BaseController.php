<?php
/**
 * Contrôleur de base
 */

class BaseController {
    protected function render($view, $data = []) {
        extract($data);
        include VIEWS_PATH . '/layouts/header.php';
        include VIEWS_PATH . '/' . $view . '.php';
        include VIEWS_PATH . '/layouts/footer.php';
    }

    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect($url) {
        // Si l'URL commence par /, utiliser BASE_URL
        if (strpos($url, '/') === 0) {
            $url = rtrim(BASE_URL, '/') . $url;
        }
        header("Location: $url");
        exit;
    }
}

