<?php

namespace Webuntis\Command;


use Memio\Memio\Config\Build;
use Memio\Model\File;
use Memio\Model\FullyQualifiedName;
use Memio\Model\Objekt;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use Webuntis\Repositories\Repository;

/**
 * Command to create a Repository
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class CreateRepositoryCommand extends Command {

    protected function configure() {
        $this->setName('webuntis:generate:repository')
            ->setDescription('Creates a new custom Repository')
            ->setHelp('This Command creates custom Repositories')
            ->addArgument('pathToModelYML', InputArgument::OPTIONAL, 'The path to the yml configuration of the model.');

    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln('<info>Creating a new Repository...</info>');
        $output->writeln('<comment>Some infos are needed for the generation</comment>');
        $helper = $this->getHelper('question');
        $fs = new Filesystem();

        if ($input->getArgument('pathToModelYML')) {
            $path = gettype($input->getArgument('pathToModelYML')) == 'array' ? $input->getArgument('pathToModelYML')[0] : $input->getArgument('pathToModelYML');
            $explodedPath = explode('.', $path);
            if (count($explodedPath) == 3) {
                $extension = $explodedPath[1] . '.' . $explodedPath[2];
                if ($extension == 'webuntis.yml') {
                    if ($fs->exists($path)) {
                        $question = new Question('Name of the repository class: ');
                        $name = $helper->ask($input, $output, $question);
                        $question = new Question('Namespace of the repository class: ');
                        $namespace = $helper->ask($input, $output, $question);
                        $question = new Question('Path to the repository class: ');
                        $classPath = $helper->ask($input, $output, $question);
                        $fullPath = $classPath . '/' . $name . '.php';
                        $file = new File($fullPath);
                        $object = new Objekt($namespace . "\\" . $name);
                        $extendFullyQualifiedName = new FullyQualifiedName(Repository::class);
                        $extend = new Objekt(Repository::class);
                        $object->extend($extend);
                        $file->addFullyQualifiedName($extendFullyQualifiedName);
                        $content = Yaml::parse(file_get_contents($path));
                        $content[array_keys($content)[0]]['repositoryClass'] = $object->getFullyQualifiedName();

                        $file->setStructure($object);
                        $prettyPrinter = Build::prettyPrinter();
                        $generatedCode = $prettyPrinter->generateCode($file);
                        $fs = new Filesystem();

                        $fs->dumpFile($fullPath, $generatedCode);
                        $fs->dumpFile($path, Yaml::dump($content, 6));
                    } else {
                        $output->writeln('<error>File does not exist</error>');
                    }
                } else {
                    $output->writeln('<error>File is no webuntis.yml file</error>');
                }
            } else {
                $output->writeln('<error>Something is wrong with the path it should be src/example.webuntis.yml</error>');
            }
        } else {
            $question = new Question('Path to the yml configuration of the model: ');
            $path = $helper->ask($input, $output, $question);
            if ($path) {
                $explodedPath = explode('.', $path);
                if (count($explodedPath) == 3) {
                    $extension = $explodedPath[1] . '.' . $explodedPath[2];
                    if ($extension == 'webuntis.yml') {
                        if ($fs->exists($path)) {
                            $question = new Question('Name of the repository class: ');
                            $name = $helper->ask($input, $output, $question);
                            $question = new Question('Namespace of the repository class: ');
                            $namespace = $helper->ask($input, $output, $question);
                            $question = new Question('Path to the repository class: ');
                            $classPath = $helper->ask($input, $output, $question);
                            $fullPath = $classPath . '/' . $name . '.php';
                            $file = new File($fullPath);
                            $object = new Objekt($namespace . "\\" . $name);
                            $extendFullyQualifiedName = new FullyQualifiedName(Repository::class);
                            $extend = new Objekt(Repository::class);
                            $object->extend($extend);
                            $file->addFullyQualifiedName($extendFullyQualifiedName);
                            $content = Yaml::parse(file_get_contents($path));
                            $content[array_keys($content)[0]]['repositoryClass'] = $object->getFullyQualifiedName();

                            $file->setStructure($object);
                            $prettyPrinter = Build::prettyPrinter();
                            $generatedCode = $prettyPrinter->generateCode($file);
                            $fs = new Filesystem();

                            $fs->dumpFile($fullPath, $generatedCode);
                            $fs->dumpFile($path, Yaml::dump($content, 6));
                        } else {
                            $output->writeln('<error>File does not exist</error>');
                        }
                } else {
                        $output->writeln('<error>File is no webuntis.yml yml file</error>');
                    }
                } else {
                    $output->writeln('<error>Something is wrong with the path it should be src/example.webuntis.yml</error>');
                }

            } else {
                $output->writeln('<error>Path is empty. If you need to create an new model use webuntis:generate:model</error>');
            }
        }
    }
}