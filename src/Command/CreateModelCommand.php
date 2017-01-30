<?php

namespace Webuntis\Command;


use Memio\Memio\Config\Build;
use Memio\Model\Argument;
use Memio\Model\Contract;
use Memio\Model\File;
use Memio\Model\FullyQualifiedName;
use Memio\Model\Method;
use Memio\Model\Object;
use Memio\Model\Phpdoc\Description;
use Memio\Model\Phpdoc\MethodPhpdoc;
use Memio\Model\Phpdoc\ParameterTag;
use Memio\Model\Phpdoc\PropertyPhpdoc;
use Memio\Model\Phpdoc\ReturnTag;
use Memio\Model\Phpdoc\VariableTag;
use Memio\Model\Property;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use Webuntis\Configuration\YAMLConfiguration;
use Webuntis\Models\AbstractModel;
use Webuntis\Types\TypeHandler;

class CreateModelCommand extends Command {
    protected function configure() {
        $this->setName('webuntis:generate:model')
            ->setDescription('Creates a new custom Model')
            ->setHelp('This Command creates custom Models');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln('<info>Creating a new Model...</info>');
        $output->writeln('<comment>Some infos are needed for the generation</comment>');
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Continue?[Y/N]: ', false);
        if (!$helper->ask($input, $output, $question)) {
            return;
        }
        $question = new Question('Model name: ');
        $modelname = $helper->ask($input, $output, $question);
        $question = new Question('Namespace: ');
        $namespace = $helper->ask($input, $output, $question);
        $question = new Question('Path to the generated Model[src]: ', 'src');
        $pathToPHPFile = $helper->ask($input, $output, $question);
        $question = new Question('Path to the generated Model[src/Resources/config]: ', 'src/Resources/config');
        $pathToYMLConfig = $helper->ask($input, $output, $question);
        $fullPathYML = $pathToYMLConfig . '/' . $modelname . '.webuntis.yml';
        $fullPath = $pathToPHPFile . '/' . $modelname . '.php';
        $file = new File($fullPath);
        $object = new Object($namespace . "\\" . $modelname);
        $ymlConfig = [$object->getFullyQualifiedName() => [
            'repositoryClass' => null,
            'fields' => [

            ]
        ]];
        $extendFullyQualifiedName = new FullyQualifiedName(AbstractModel::class);
        $extend = new Object(AbstractModel::class);
        $object->extend($extend);
        $file->addFullyQualifiedName($extendFullyQualifiedName);
        $output->writeln('<comment>Now you can add some fields</comment>');
        $types = '';
        $allTypes = TypeHandler::getAllTypes();
        foreach ($allTypes as $key => $value) {
            $types .= ' ' . $key;
        }
        $output->writeln('<info>Available Types: ' . $types . '</info>');
        $question = new Question('Name of the attribute you want to add [enter if finished]: ');
        $name = $helper->ask($input, $output, $question);
        if($name) {
            $question = new Question('Type of ' . $name . ': ');
            $attribute = $helper->ask($input, $output, $question);
            if (isset($allTypes[$attribute])) {
                $property = new Property($name);
                $phpDoc = new PropertyPhpdoc();
                $phpDoc->setVariableTag(new VariableTag($allTypes[$attribute]::getType()));
                $property->setPhpdoc($phpDoc);
                $object->addProperty($property);
                if(!in_array(FullyQualifiedName::make($allTypes[$attribute]::getType()), $file->allFullyQualifiedNames()) && $allTypes[$attribute]::getName() != 'model' && $allTypes[$attribute]::getName() != 'modelCollection') {
                    $file->addFullyQualifiedName(FullyQualifiedName::make($allTypes[$attribute]::getType()));
                }
                $getter = Method::make('get' . ucfirst($name))->setBody('        return $this->' . $name . ';');
                $phpDoc = new MethodPhpdoc();
                $phpDoc->setDescription(new Description('Getter for ' . $name));
                $phpDoc->setReturnTag(new ReturnTag($allTypes[$attribute]::getType(), $name));
                $getter->setPhpdoc($phpDoc);
                $object->addMethod($getter);
                if(!strpos($allTypes[$attribute]::getType(), '[]')) {
                    $setterArgument = Argument::make($allTypes[$attribute]::getType(), $name);
                }else {
                    $setterArgument = Argument::make('array', $name);
                }
                $setter = Method::make('set' . ucfirst($name))->addArgument($setterArgument)->setBody('        $this->' . $name . ' = $' . $name . ';');
                $phpDoc = new MethodPhpdoc();
                $phpDoc->setDescription(new Description('Setter for ' . $name));
                $phpDoc->addParameterTag(new ParameterTag($allTypes[$attribute]::getType(), $name));
                $setter->setPhpdoc($phpDoc);
                $object->addMethod($setter);
                $ymlConfig[$object->getFullyQualifiedName()]['fields'][$name] = $allTypes[$attribute]::generateTypeWithConsole($output, $input, $helper);
            } else {
                $output->writeln('<error>Type does not exist</error>');
            }
        }

        while ($name) {
            $question = new Question('Name of the attribute you want to add [enter if finished]: ');
            $name = $helper->ask($input, $output, $question);
            if($name) {
                $question = new Question('Type of ' . $name . ': ');
                $attribute = $helper->ask($input, $output, $question);
                if (isset($allTypes[$attribute])) {
                    $property = new Property($name);
                    $phpDoc = new PropertyPhpdoc();
                    $phpDoc->setVariableTag(new VariableTag($allTypes[$attribute]::getType()));
                    $property->setPhpdoc($phpDoc);
                    if(!in_array(FullyQualifiedName::make($allTypes[$attribute]::getType()), $file->allFullyQualifiedNames())  && $allTypes[$attribute]::getName() != 'model' && $allTypes[$attribute]::getName() != 'modelCollection') {
                        $file->addFullyQualifiedName(FullyQualifiedName::make($allTypes[$attribute]::getType()));
                    }
                    $object->addProperty($property);
                    $getter = Method::make('get' . ucfirst($name))->setBody('        return $this->' . $name . ';');
                    $phpDoc = new MethodPhpdoc();
                    $phpDoc->setDescription(new Description('Getter for ' . $name));
                    $phpDoc->setReturnTag(new ReturnTag($allTypes[$attribute]::getType(), $name));
                    $getter->setPhpdoc($phpDoc);
                    $object->addMethod($getter);
                    if(!strpos($allTypes[$attribute]::getType(), '[]')) {
                        $setterArgument = Argument::make($allTypes[$attribute]::getType(), $name);
                    }else {
                        $setterArgument = Argument::make('array', $name);
                    }
                    $setter = Method::make('set' . ucfirst($name))->addArgument($setterArgument)->setBody('        $this->' . $name . ' = $' . $name . ';');
                    $phpDoc = new MethodPhpdoc();
                    $phpDoc->setDescription(new Description('Setter for ' . $name));
                    $phpDoc->addParameterTag(new ParameterTag($allTypes[$attribute]::getType(), $name));
                    $setter->setPhpdoc($phpDoc);
                    $object->addMethod($setter);
                    $ymlConfig[$object->getFullyQualifiedName()]['fields'][$name] = $allTypes[$attribute]::generateTypeWithConsole($output, $input, $helper);
                } else {
                    $output->writeln('<error>Type does not exist</error>');
                }
            }
        }
        $setArgument1 = Argument::make('mixed', 'field');
        $setArgument2 = Argument::make('mixed', 'value');
        $set = Method::make('set')->addArgument($setArgument1)->addArgument($setArgument2)->setBody('        $this->$field = $value;');
        $phpDoc = new MethodPhpdoc();
        $phpDoc->setDescription(new Description('sets an given field'));
        $phpDoc->addParameterTag(new ParameterTag('mixed', 'field'));
        $phpDoc->addParameterTag(new ParameterTag('mixed', 'value'));
        $set->setPhpdoc($phpDoc);
        $object->addMethod($set);

        $file->setStructure($object);
        $prettyPrinter = Build::prettyPrinter();
        $generatedCode = $prettyPrinter->generateCode($file);
        $fs = new Filesystem();
        umask(0002);

        $fs->dumpFile($fullPath, $generatedCode);
        $fs->dumpFile($fullPathYML, Yaml::dump($ymlConfig, 6));
    }
}