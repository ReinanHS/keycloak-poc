<?php

use Application\Controller\AuthController;
use Application\Middleware\AuthMiddleware;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add routes
$app->get('/', AuthController::class);

// Add middleware
$app->add(AuthMiddleware::class);

$app->run();