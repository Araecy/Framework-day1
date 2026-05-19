<?php
//front controller
// error reporting
error_reporting(E_ALL);
error_reporting(-1);
ini_set('error_reporting', E_ALL);

use Araecy\Framework\Http\Request;
use Araecy\Framework\Http\Response;
use Araecy\Framework\Http\Kernel;

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

session_start();

$request = Request::create();

$kernel = new Kernel();

$response = $kernel->handle($request);

$response->send();
