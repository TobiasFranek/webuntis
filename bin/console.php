<?php

if(file_exists(__DIR__.'/../../../../vendor/autoload.php')) {
    require_once __DIR__.'/../../../../vendor/autoload.php';
} else {
    require_once __DIR__.'/../vendor/autoload.php';

}

use Symfony\Component\Console\Application;
use Webuntis\Command\CreateModelCommand;
use Webuntis\Command\CreateRepositoryCommand;
use Webuntis\Command\CacheClearCommand;

$application = new Application();

$application->add(new CreateModelCommand());
$application->add(new CreateRepositoryCommand());
$application->add(new CacheClearCommand());

$application->run();