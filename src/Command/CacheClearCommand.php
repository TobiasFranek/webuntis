<?php

namespace Webuntis\Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Webuntis\CacheBuilder\Routines\MemcacheRoutine;
use Webuntis\CacheBuilder\CacheBuilder;

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
            ->addArgument('config', InputArgument::OPTIONAL|InputArgument::IS_ARRAY, 'config of the caching server')
            ->addOption('routine', 'r', InputOption::VALUE_OPTIONAL, 'caching routine', []);
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $helper = $this->getHelper('question');
        $routine = MemcacheRoutine::class;
        $config = $input->getArgument('config');
        $customRoutine = $input->getOption('routine');
        if ($customRoutine) {
            $routine = $customRoutine;
        }

        $configMeta = $routine::getConfigMeta();

        $cacheConfig = [];

        foreach ($configMeta as $i => $value) {
            if (isset($config[$i])) {
                $cacheConfig[$value['name']] = $config[$i];
            } else {
                $question = new Question($value['question'], $value['default']);
                $cacheConfig[$value['name']] = $helper->ask($input, $output, $question);
            }
        }

        $cacheConfig['type'] = $routine::getName();

        if ($customRoutine) {
            $cacheConfig['routine'][$routine::getName()] = $routine;
        }

        $cacheBuilder = new CacheBuilder($cacheConfig);

        $cache = $cacheBuilder->create();

        if ($cache) {
            $cache->deleteAll();
            $output->writeln('<info>Successfully cleared the cache</info>');
        } else {
            $output->writeln('<error>extension memcached not found</error>');
        }
    }
}