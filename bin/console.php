<?php

require __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Webuntis\Command\CreateModelCommand;

$application = new Application();

$application->add(new CreateModelCommand());

$application->run();