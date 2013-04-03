<?php

ini_set('display_errors', 0);

require_once __DIR__.'/../vendor/autoload.php';

$app = require __DIR__ . '/../app/app.php';
require __DIR__.'/../app/bootstrap.php';
require __DIR__ . '/../app/controllers.php';
require __DIR__ . '/../app/admin.controllers.php';
$app->mount('/admin',$admin);
$app->run();
