<?php declare(strict_types=1);

use PhpMicroframework\Framework\Problem;
use Website\Application\Application;
use Website\Application\ApplicationRouter;
use Website\Application\Controller\ApplicationController;

require dirname(__DIR__) . '/vendor/autoload.php';

error_reporting(E_ALL | E_STRICT);
date_default_timezone_set('UTC');

Problem::setDisplayErrorsEnabled(true);

// Route all requests through single controller method.
ApplicationRouter::$controller = ApplicationController::class;
ApplicationRouter::$method = 'handle';

// Run the framework
Application::run();
