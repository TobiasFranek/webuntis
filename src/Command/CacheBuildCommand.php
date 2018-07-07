<?php

namespace Webuntis\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Webuntis\Configuration\WebuntisConfiguration;
use Webuntis\Configuration\YAMLConfiguration;
use Webuntis\Models\Interfaces\CachableModelInterface;
use Webuntis\Query\Query;
use Webuntis\Security\WebuntisSecurityManager;
use Webuntis\CacheBuilder\CacheBuilder;
use Webuntis\CacheBuilder\Routines\MemcacheRoutine;

/**
 * Command to build the Cache
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class CacheBuildCommand extends Command {
    protected function configure() {
        $this->setName('webuntis:cache:build')
            ->setDescription('builds the webuntis cache')
            ->setHelp('This Command builds the webuntis cache')
            ->addArgument('config', InputArgument::OPTIONAL|InputArgument::IS_ARRAY, 'config of the whole server + username and password + caching')
            ->addOption('exclude', 'e', InputOption::VALUE_OPTIONAL|InputOption::VALUE_IS_ARRAY, 'exclude models', [])
            ->addOption('routine', 'r', InputOption::VALUE_OPTIONAL, 'caching routine', [])
            ->addOption('securityManager', 's', InputOption::VALUE_OPTIONAL, 'Security Manager', []);
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $helper = $this->getHelper('question');
        $excluded = $input->getOption('exclude');
        $config = $input->getArgument('config');

        $routine = MemcacheRoutine::class;
        $customRoutine = $input->getOption('routine');
        if($customRoutine) {
            $routine = $customRoutine;
        }

        $manager = WebuntisSecurityManager::class;
        $customManager = $input->getOption('securityManager');
        if($customManager) {
            $manager = $customManager;
        }

        $cacheConfigMeta = $routine::getConfigMeta();
        $managerConfigMeta = $manager::getConfigMeta();

        $parsedConfig = [
            'admin' => [],
            'cache' => [],
            'only_admin' => true
        ];

        foreach($managerConfigMeta as $i => $value) {
            if(isset($config[$i])) {
                $parsedConfig['admin'][$value['name']] = $config[$i];
            } else {
                $question = new Question($value['question'], $value['default']);
                $parsedConfig['admin'][$value['name']] = $helper->ask($input, $output, $question);
            }
        }

        foreach($cacheConfigMeta as $i => $value) {
            if(isset($config[$i + count($managerConfigMeta)])) {
                $parsedConfig['cache'][$value['name']] = $config[$i + count($managerConfigMeta)];
            } else {
                $question = new Question($value['question'], $value['default']);
                $parsedConfig['cache'][$value['name']] = $helper->ask($input, $output, $question);
            }
        }
        $cacheClearArgs = $parsedConfig['cache'];
        $parsedConfig['cache']['type'] = $routine::getName();
        if($customRoutine) {
            $parsedConfig['cache']['routine'][$routine::getName()] = $routine;
        }

        if($customManager) {
            $parsedConfig['security_manager'] = $manager;
        }
        $command = $this->getApplication()->find('webuntis:cache:clear');

        $arguments = [
            'config' => array_values($cacheClearArgs),
        ];

        if($customRoutine) {
            $arguments['--routine'] = $routine;
        }

        $argumentInput = new ArrayInput($arguments);

        $command->run($argumentInput, $output);

        new WebuntisConfiguration($parsedConfig);

        $query = new Query();

        $ymlConfig = new YAMLConfiguration();

        $models = $ymlConfig->getModels();
        
        foreach ($excluded as $modelName) {
            unset($models[$modelName]);
        }
        foreach ($models as $key => $value) {
            $interfaces = class_implements($value);
            if (isset($interfaces[CachableModelInterface::class]) || $key == 'Exams') {
                $output->writeln($key);
                $query->get($key)->findAll();
            }
        }
        $output->writeln('<info>successfully built cache!</info>');
    }
}