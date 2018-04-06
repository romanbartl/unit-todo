<?php

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Nette\Configurator;

//$array = array("46.13.152.31", "90.183.127.2", "46.167.201.252");

$configurator->setDebugMode(true);
$configurator->enableDebugger(__DIR__ . '/../log');

$configurator->setTimeZone('Europe/Prague');
$configurator->setTempDirectory(__DIR__ . '/../temp');

$configurator->createRobotLoader()
    ->addDirectory(__DIR__)
    ->register();

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon');

//hezké URL, jednou..
/*
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;
$router = new RouteList();
$router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');
$router[] = new Route('hrac/<action>[<user>]', 'Hrac:default');
*/
$container = $configurator->createContainer();
//$container->addService('router', $router);

return $container;
