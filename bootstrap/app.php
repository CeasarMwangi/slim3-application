<?php
use Respect\Validation\Validator as v;
session_start(); //Start session
/**
 * Require our dependencies
 */
require __DIR__ . '/../vendor/autoload.php';


/**
 * Instantiate slime to create a slim instance and pass settings
 */

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
        'db' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'icloud',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]
    ]
]);

$container = $app->getContainer();

//adding illuminate database
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();


//registering/binding items with the container

$container['db'] = function($container) use ($capsule){
    return $capsule;
};



$container['auth'] = function($container){
    return new App\Auth\Auth;
};

$container['flash'] = function($container){
    return new Slim\Flash\Messages;
};

$container['view'] = function($container){
    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
        'cache' => false,
    ]);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));

    //add the auth to the global so that its accessible from all the views
    $view->getEnvironment()->addGlobal('auth', [
        'check' => $container->auth->check(),
        'user' => $container->auth->user()
    ]);

    //add the flash to the global so that it is available globally from all the views
    $view->getEnvironment()->addGlobal('flash',$container->flash);


    return $view;
};

$container['validator'] = function($container){
    return new App\Validation\Validator();
};

$container['HomeController'] = function($container){
    return new App\Controllers\HomeController($container);
};

$container['AuthController'] = function($container){
    return new App\Controllers\Auth\AuthController($container);
};

$container['PasswordController'] = function($container){
    return new App\Controllers\Auth\PasswordController($container);
};

$container['csrf'] = function($container){
    return new Slim\Csrf\Guard;
};

$app->add(new App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new App\Middleware\OldInputMiddleware($container));
$app->add(new App\Middleware\CsrfViewMiddleware($container));

$app->add($container->csrf);

v::with('App\\Validation\\Rules\\');

require __DIR__ . '/../app/routes.php';