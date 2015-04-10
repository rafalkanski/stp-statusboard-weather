<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

$app->mount('', new \StpBoard\Weather\WeatherControllerProvider());

$app->run();
