#!/usr/bin/env php

<?php

if (PHP_SAPI !== 'cli') {
    echo 'Warning: Clapi should be invoked via the CLI version of PHP, not the '.PHP_SAPI.' SAPI'.PHP_EOL;
}

require_once __DIR__ . '/../vendor/autoload.php';

use Clapi\Command\CallCommand;
use Symfony\Component\Console\Application;

$app = new Application();
$app->add(new CallCommand());
$app->run();