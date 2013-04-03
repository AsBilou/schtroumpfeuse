<?php

use Symfony\Component\ClassLoader\DebugClassLoader;
use Symfony\Component\HttpKernel\Debug\ErrorHandler;
use Symfony\Component\HttpKernel\Debug\ExceptionHandler;

require_once __DIR__.'/../vendor/autoload.php';

error_reporting(-1);
DebugClassLoader::enable();
ErrorHandler::register();
if ('cli' !== php_sapi_name()) {
    ExceptionHandler::register();
}

$app = require __DIR__ . '/../app/app.php';
require __DIR__.'/../app/dev.bootstrap.php';
require __DIR__ . '/../app/controllers.php';
require __DIR__ . '/../app/admin.controllers.php';
$app->mount('/admin',$admin);
$app->run();
