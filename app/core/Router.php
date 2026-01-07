<?php
/**
 * Routeur simple pour l'application
 */

class Router {
    private $routes = [];

    public function route($page, $action = 'index') {
        $controllerName = ucfirst($page) . 'Controller';
        $controllerFile = APP_PATH . '/controllers/' . $controllerName . '.php';

        if (!file_exists($controllerFile)) {
            http_response_code(404);
            include VIEWS_PATH . '/errors/404.php';
            return;
        }

        // Charger BaseController si nécessaire
        if (!class_exists('BaseController')) {
            require_once APP_PATH . '/controllers/BaseController.php';
        }

        require_once $controllerFile;

        if (!class_exists($controllerName)) {
            http_response_code(500);
            die("Contrôleur $controllerName introuvable");
        }

        $controller = new $controllerName();
        $method = $action . 'Action';

        if (!method_exists($controller, $method)) {
            http_response_code(404);
            include VIEWS_PATH . '/errors/404.php';
            return;
        }

        $controller->$method();
    }

    public function dispatch() {
        $page = $_GET['page'] ?? 'login';
        $action = $_GET['action'] ?? 'index';

        // Pages publiques (sans authentification)
        $publicPages = ['login'];

        if (!in_array($page, $publicPages)) {
            Auth::requireLogin();
        }

        $this->route($page, $action);
    }
}

