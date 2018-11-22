<?php
//error_reporting(E_STRICT);
declare(strict_types=1);
//phpinfo();

ini_set('session.cookie_lifetime', '864000');   // ten days in seconds

use PHPMailer\PHPMailer\PHPMailer;

/**
 * Twig
 */
require_once dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

session_start();

//var_dump($_COOKIE['remember_me']);
//var_dump($_SESSION);
//var_dump(session_get_cookie_params());
//var_dump($_SERVER);

//require_once dirname(__FILE__) . '/public/securimage/securimage.php';

/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('login', ['controller' => 'Accounts', 'action' => 'login']);
$router->add('logout', ['controller' => 'Accounts', 'action' => 'logout']);
$router->add('password/reset/{token:[\da-f]+}', ['controller' => 'Password', 'action' => 'reset']);
$router->add('accounts/activated/{token:[\da-f]+}', ['controller' => 'Accounts', 'action' => 'activate']);
$router->add('{controller}/{action}');


$router->dispatch($_SERVER['QUERY_STRING']);