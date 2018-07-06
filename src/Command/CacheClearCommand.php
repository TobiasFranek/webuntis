<?php

namespace Webuntis\Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Webuntis\CacheBuilder\Cache\Memcached;


/**
 * Command to clear the Cache
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class CacheClearCommand extends Command {
    protected function configure() {
        $this->setName('webuntis:cache:clear')
            ->setDescription('cleares the webuntis cache')
            ->setHelp('This Command clears the webuntis cache')
            ->addArgument('port', InputArgument::OPTIONAL, 'port of the memcached server')
            ->addArgument('host', InputArgument::OPTIONAL, 'host of the memcached server');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $helper = $this->getHelper('question');
        if (!$port = $input->getArgument('port')) {
            $question = new Question('Port [11211]: ', 11211);
            $port = $helper->ask($input, $output, $question);

        }
        if (!$host = $input->getArgument('host')) {
            $question = new Question('Host [localhost]: ', 'localhost');
            $host = $helper->ask($input, $output, $question);
        }

        $cacheDriver = new Memcached();
        if (extension_loaded('memcached')) {
            $memcached = new \Memcached();
            $memcached->addServer($host, $port);
            $cacheDriver->setMemcached($memcached);
            $cacheDriver->deleteAll();
            $output->writeln('<info>Successfully cleared the cache</info>');
        } else {
            $output->writeln('<error>extension memcached not found</error>');
        }
    }
}