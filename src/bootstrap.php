<?php declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Nette\Configurator;

$configurator->setDebugMode(true);
$configurator->setTimeZone('Europe/Prague');

$configurator->enableTracy(__DIR__ . '/../log/');

$configurator->setTempDirectory(__DIR__ . '/../temp/');

$configurator->addConfig(__DIR__ . '/../config/config.neon');

return $configurator->createContainer();
