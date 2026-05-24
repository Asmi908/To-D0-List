<?php

namespace App\core;
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class Router
{
    public function dispatch(string $url)
    {
        $url = strtolower(trim($url));
        $urlParts = explode('/', rtrim($url, '/'));

        $controllerName = ucfirst($urlParts[0]) . 'Controller';
        $method = $urlParts[1] ?? 'index';
        $params = array_slice($urlParts, 2);

        $controllerClass = "App\\controllers\\$controllerName";

        // Public controllers (like login/register)
        $publicControllers = ['auth'];

        if (!in_array($urlParts[0], $publicControllers) && !isset($_SESSION['user_id'])) {
            header('Location: ?url=auth/login');
            exit;
        }

        if (!class_exists($controllerClass)) {
            http_response_code(404);
            echo "404 - Controller '$controllerClass' not found.";
            return;
        }

        $controller = new $controllerClass();

        if (method_exists($controller, 'handleRequest')) {
            $response = $controller->handleRequest();

            if (!isset($response['view'])) {
                echo "Error: No view specified.";
                return;
            }

            $viewFile = __DIR__ . '/../views/' . $response['view'] . '.php';
            $data = $response['data'] ?? [];

            if (file_exists($viewFile)) {
                extract($data);
                include $viewFile;
            } else {
                echo "Error: View file not found: $viewFile";
            }
        } elseif (method_exists($controller, $method)) {
            call_user_func_array([$controller, $method], $params);
        } else {
            http_response_code(404);
            echo "Error: Method '$method' not found in controller '$controllerClass'.";
        }
    }
}
