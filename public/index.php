<?php

declare(strict_types=1);

use Application\Controller\AuthController;
use Application\Controller\HomeController;
use Application\Controller\UserController;
use Application\Middleware\AuthMiddleware;
use DI\Container;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Load dependencies
$dependencies = require __DIR__ . '/../config/dependencies.php';

// Create Container using PHP-DI
$container = new Container($dependencies);

// Set container to create App with on AppFactory
AppFactory::setContainer($container);

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add routes
$app->get('/', HomeController::class);
$app->get('/auth', AuthController::class);
$app->get('/user', UserController::class)->add(AuthMiddleware::class);

$app->run();