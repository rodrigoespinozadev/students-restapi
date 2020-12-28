<?php 

define('BASE_PATH', realpath(__DIR__) .'/');
define('APP_PATH', realpath(__DIR__ . '/./App/'));
define('SYSTEM_PATH', realpath(__DIR__ . '/./System/'));
define('CONTROLLERS', APP_PATH . '/Controllers/');
define('MODELS', APP_PATH . '/Models/');

define('DATABASE', [
    'port'   => 3306,
    'hostname'   => 'mysql',
    'driver' => 'PDO',
    'database'   => 'students',
    'username'   => 'students',
    'password'   => 'secret'
]);

define('COOKIE', [
	'timeout' => time()+(60*60*4),
	'expires' => 0,
	'path' => '/',
	'domain' => '',
	'secure' => '',
	'httponly' => '',
]);