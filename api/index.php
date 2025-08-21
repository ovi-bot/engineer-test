<?php

use App\App;
use App\Http\ErrorHandler;
use App\Http\Request;
use App\Routing\Dispatcher;
use App\Routing\Router;

require_once __DIR__ . '/src/bootstrap.php';

ErrorHandler::registerAll();

$app = new App(new Router(), new Dispatcher());

$request = Request::generate();
$app->run($request);