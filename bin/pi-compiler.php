<?php declare(strict_types = 1);

$container = require __DIR__ . '/../src/bootstrap.php';

$app = $container->getByType(\Symfony\Component\Console\Application::class);

exit($app->run());
