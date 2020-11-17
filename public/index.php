<?php
/**
 * Front controller
 */

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';

use App\Config;
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    "driver" => "mysql",
    "host" => Config::DB_HOST,
    "database" => Config::DB_NAME,
    "username" => Config::DB_USER,
    "password" => Config::DB_PASSWORD
]);

//Make this Capsule instance available globally.
$capsule->setAsGlobal();
// Setup the Eloquent ORM.
$capsule->bootEloquent();

session_start();

/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/**
 * Routing
 */
$router = new Core\Router();


// Add the routes
$router->add('', ['controller' => 'TaskController', 'action' => 'index', 'auth_always' => true]);
$router->add('tasks/add', ['controller' => 'TaskController', 'action' => 'add', 'auth_always' => true]);
$router->add('tasks/store', ['controller' => 'TaskController', 'action' => 'store', 'auth_always' => true]);
$router->add('tasks/{id:\d+}/edit', ['controller' => 'TaskController', 'action' => 'edit', 'auth' => true]);
$router->add('tasks/{id:\d+}/update', ['controller' => 'TaskController', 'action' => 'update', 'auth' => true]);
$router->add('sign-in', ['controller' => 'AuthController', 'action' => 'login', 'auth' => false]);
$router->add('login',   ['controller' => 'AuthController', 'action' => 'loginPost', 'auth' => false]);
$router->add('logout', ['controller' => 'AuthController', 'action' => 'logout', 'auth' => true]);

$router->dispatch($_SERVER['QUERY_STRING']);
