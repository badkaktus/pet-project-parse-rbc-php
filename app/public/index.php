<?php

/*
 * Created by Nickolay Sinyukhin on 03.04.2020 00:13
 * Copyright (c) 03.04.2020 00:13. All right reserved
 *
 * Last modified 03.04.2020 00:13 
 *
 * ¯\_(ツ)_/¯
 */

require __DIR__ . '/../vendor/autoload.php';
$conf = require __DIR__ . '/../config/db.php';

use Pimple\Container;
use Twig\Loader\FilesystemLoader;
use Doctrine\DBAL\DriverManager;
use Twig\Environment;

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', 'App\Controller\NewsController');
    $r->addRoute('GET', '/news/{id:\d+}', 'App\Controller\NewsController');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo 'not found';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        $di = new Container();

        $di['db'] = DriverManager::getConnection($conf['app']);

        $loader = new FilesystemLoader(__DIR__ . '/../templates');
        $di['twig'] = new Environment($loader);

        $app = new $handler($di);

        $app->exec($vars);

        break;
}