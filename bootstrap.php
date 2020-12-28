<?php 

session_start();
require 'config.php';

use System\Cookie;
use System\Http\Request;
use System\Http\Response;
use System\Router\Router;

function autoload($class) {
	$file = BASE_PATH . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file))
        require_once $file;
    else
        throw new Exception(sprintf('Class { %s } Not Found!', $class));
}

spl_autoload_register('autoload');

if (PHP_VERSION_ID < 70400) {
	throw new Exception('Invalid PHP Version minimum required 7.4.0');
}

$request = new Request;
$response = new Response;
$router = new Router(
    $request, 
    $response
);

require 'routes.php';

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header('Content-Type: application/json; charset=UTF-8');
header("Expires: Sun, 19 Nov 1978 05:00:00 GMT");
header("Last-Modified: ". gmdate("D, d M Y H:i:s") ." GMT");
header("Cache-Control: store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", FALSE);

