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

/**
 * Command to build the Cache
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class CacheBuildCommand extends Command{
    protected function configure() {
        $this->setName('webuntis:cache:build')
            ->setDescription('builds the webuntis cache')
            ->setHelp('This Command builds the webuntis cache')
            ->addArgument('server', InputArgument::OPTIONAL, 'server whch the school is based in')
            ->addArgument('school', InputArgument::OPTIONAL, 'school')
            ->addArgument('adminusername', InputArgument::OPTIONAL, 'the admin username')
            ->addArgument('adminpassword', InputArgument::OPTIONAL, 'the admin password')
            ->addArgument('memcachedhost', InputArgument::OPTIONAL, 'the memcached host')
            ->addArgument('memcachedport', InputArgument::OPTIONAL, 'the memcached port')
            ->addOption('exclude', 'e', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'exclude models', []);
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        if(!extension_loaded('memcached')) {
            $output->writeln('<error>extension memcached not found</error>');
            return;
        }

        $helper = $this->getHelper('question');
        if(!$server = $input->getArgument('server')) {
            $question = new Question('Server of the school: ');
            $server = $helper->ask($input, $output, $question);
        }
        if(!$school = $input->getArgument('school')) {
            $question = new Question('school: ');
            $school = $helper->ask($input, $output, $question);
        }
        $admin = [];
        if(!$admin['username'] = $input->getArgument('adminusername')) {
            $question = new Question('Admin username: ');
            $admin['username'] = $helper->ask($input, $output, $question);
        }
        if(!$admin['password'] = $input->getArgument('adminpassword')) {
            $question = new Question('Admin password: ');
            $admin['password'] = $helper->ask($input, $output, $question);
        }
        if(!$memcachedPort = $input->getArgument('memcachedport')) {
            $question = new Question('Memcached host[11211]: ', 11211);
            $memcachedPort = $helper->ask($input, $output, $question);
        }
        if(!$memcachedHost = $input->getArgument('memcachedhost')) {
            $question = new Question('Memcached host[localhost]: ', 'localhost');
            $memcachedHost = $helper->ask($input, $output, $question);
        }
        $excluded = $input->getOption('exclude');
        $command = $this->getApplication()->find('webuntis:cache:clear');

        $arguments = [
            'host' => $memcachedHost,
            'port' => $memcachedPort
        ];

        $argumentInput = new ArrayInput($arguments);

        $returnCode = $command->run($argumentInput, $output);

        $config = new WebuntisConfiguration([
            'admin' => [
                'server' => $server,
                'school' => $school,
                'username' => $admin['username'],
                'password' => $admin['password']
            ],
            'only_admin' => true
        ]);

        $query = new Query();

        $ymlConfig = new YAMLConfiguration();

        $models = $ymlConfig->getModels();
        
        foreach($excluded as $modelName) {
            unset($models[$modelName]);
        }
        foreach($models as $key => $value) {
            $interfaces = class_implements($value);
            if(isset($interfaces[CachableModelInterface::class]) || $key == 'Exams') {
                $output->writeln($key);
                $query->get($key)->findAll();
            }
        }
        $output->writeln('<info>successfully built cache!</info>');
    }
}