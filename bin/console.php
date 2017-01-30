<?php

require __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Webuntis\Command\CreateModelCommand;
use Webuntis\Command\CreateRepositoryCommand;

$application = new Application();

$application->add(new CreateModelCommand());
$application->add(new CreateRepositoryCommand());

$application->run();