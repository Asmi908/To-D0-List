<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



session_start(); //  VERY IMPORTANT: MUST be the first thing
require_once __DIR__ . '/../vendor/autoload.php';

use App\core\Router;

$url = $_GET['url'] ?? '';

if (!$url) {
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $scriptName = dirname($_SERVER['SCRIPT_NAME']);
    $url = trim(str_replace($scriptName, '', $requestUri), '/');
}

$url = $url ?: 'category/index';

$router = new Router();
$router->dispatch($url);

?>