<?php
//front controller
// error reporting
error_reporting(E_ALL);
error_reporting(-1);
ini_set('error_reporting', E_ALL);

use Araecy\Framework\Http\Request;
use Araecy\Framework\Http\Response;
use Araecy\Framework\Http\Kernel;
// Let the built-in PHP server serve existing files (CSS, JS, images).
if (PHP_SAPI === 'cli-server') {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $file = __DIR__ . $path;

    if (is_file($file)) {
        return false;
    }
}
define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

session_start();

$request = Request::create();

$kernel = new Kernel();

$response = $kernel->handle($request);

$response->send();
