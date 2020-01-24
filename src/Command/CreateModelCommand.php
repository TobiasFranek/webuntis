<?php
namespace Webuntis\Command;


use Memio\Memio\Config\Build;
use Memio\Model\Argument;
use Memio\Model\Constant;
use Memio\Model\Contract;
use Memio\Model\File;
use Memio\Model\FullyQualifiedName;
use Memio\Model\Method;
use Memio\Model\Objekt;
use Memio\Model\Phpdoc\Description;
use Memio\Model\Phpdoc\MethodPhpdoc;
use Memio\Model\Phpdoc\ParameterTag;
use Memio\Model\Phpdoc\PropertyPhpdoc;
use Memio\Model\Phpdoc\ReturnTag;
use Memio\Model\Phpdoc\VariableTag;
use Memio\Model\Property;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use Webuntis\Exceptions\ModelException;
use Webuntis\Models\AbstractModel;
use Webuntis\Models\Interfaces\AdministrativeModelInterface;
use Webuntis\Models\Interfaces\CachableModelInterface;
use Webuntis\Types\TypeHandler;

/**
 * Command to create a Model
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
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
        $object = new Objekt($namespace . "\\" . $modelname);
        $ymlConfig = [$object->getFullyQualifiedName() => [
            'repositoryClass' => null,
            'fields' => [

            ]
        ]];
        $extendFullyQualifiedName = new FullyQualifiedName(AbstractModel::class);
        $extend = new Objekt(AbstractModel::class);
        $object->extend($extend);
        $file->addFullyQualifiedName($extendFullyQualifiedName);
        $question = new ConfirmationQuestion('Is your model cachable?[Y/N]: ', false);
        if ($helper->ask($input, $output, $question)) {
            $object->implement(new Contract(CachableModelInterface::class));
            $file->addFullyQualifiedName(new FullyQualifiedName(CachableModelInterface::class));
            $question = new Question('how long should your cache life time be [0]: ', 0);
            $cacheLifetime = intval($helper->ask($input, $output, $question));
            $object->addConstant(new Constant('CACHE_LIFE_TIME', $cacheLifetime));
        }
        $question = new ConfirmationQuestion('Can your model only be executed with special rights?[Y/N]: ', false);
        if ($helper->ask($input, $output, $question)) {
            $object->implement(new Contract(AdministrativeModelInterface::class));
            $file->addFullyQualifiedName(new FullyQualifiedName(AdministrativeModelInterface::class));
        }
        $question = new Question('What is the corresponding API method: ');
        $method = $helper->ask($input, $output, $question);
        $object->addConstant(new Constant('METHOD', '"' . $method . '"'));
        $output->writeln('<comment>Now you can add some fields</comment>');
        $types = '';
        $allTypes = TypeHandler::getAllTypes();
        foreach ($allTypes as $key => $value) {
            $types .= ' ' . $key;
        }
        $models = [];
        $output->writeln('<info>Available Types: ' . $types . '</info>');
        $question = new Question('Name of the attribute you want to add [enter if finished]: ');
        $name = $helper->ask($input, $output, $question);
        while ($name) {
            if ($name) {
                $question = new Question('Type of ' . $name . ': ');
                $attribute = $helper->ask($input, $output, $question);
                if (isset($allTypes[$attribute])) {
                    $property = new Property($name);
                    $phpDoc = new PropertyPhpdoc();
                    $phpDoc->setVariableTag(new VariableTag($allTypes[$attribute]::getType()));
                    $property->setPhpdoc($phpDoc);
                    if (!in_array(new FullyQualifiedName($allTypes[$attribute]::getType()), $file->allFullyQualifiedNames()) && $allTypes[$attribute]::getName() != 'model' && $allTypes[$attribute]::getName() != 'modelCollection') {
                        $file->addFullyQualifiedName(new FullyQualifiedName($allTypes[$attribute]::getType()));
                    }
                    $object->addProperty($property);
                    $getter = new Method('get' . ucfirst($name));
                    $getter->setBody('        return $this->' . $name . ';');
                    $phpDoc = new MethodPhpdoc();
                    $phpDoc->setDescription(new Description('Getter for ' . $name));
                    $phpDoc->setReturnTag(new ReturnTag($allTypes[$attribute]::getType()));
                    $getter->setPhpdoc($phpDoc);
                    $object->addMethod($getter);
                    if (!strpos($allTypes[$attribute]::getType(), '[]')) {
                        $setterArgument = new Argument($allTypes[$attribute]::getType(), $name);
                    } else {
                        $setterArgument = new Argument('array', $name);
                    }
                    $setter = new Method('set' . ucfirst($name));
                    $setter->addArgument($setterArgument)->setBody('        $this->' . $name . ' = $' . $name . ';');
                    $phpDoc = new MethodPhpdoc();
                    $phpDoc->setDescription(new Description('Setter for ' . $name));
                    $phpDoc->addParameterTag(new ParameterTag($allTypes[$attribute]::getType(), $name));
                    $setter->setPhpdoc($phpDoc);
                    $object->addMethod($setter);
                    $ymlConfig[$object->getFullyQualifiedName()]['fields'][$name] = $allTypes[$attribute]::generateTypeWithConsole($output, $input, $helper);
                    if ($attribute == 'model' || $attribute == 'modelCollection') {
                        $models[] = $name;
                    }
                } else {
                    $output->writeln('<error>Type does not exist</error>');
                }
            }
            $question = new Question('Name of the attribute you want to add [enter if finished]: ');
            $name = $helper->ask($input, $output, $question);
        }
        $setArgument1 = new Argument('mixed', 'field');
        $setArgument2 = new Argument('mixed', 'value');
        $set = new Method('set');
        $set->addArgument($setArgument1)->addArgument($setArgument2)->setBody('        $this->$field = $value;');
        $phpDoc = new MethodPhpdoc();
        $phpDoc->setDescription(new Description('sets an given field'));
        $phpDoc->addParameterTag(new ParameterTag('mixed', 'field'));
        $phpDoc->addParameterTag(new ParameterTag('mixed', 'value'));
        $set->setPhpdoc($phpDoc);
        $object->addMethod($set);

        if (!empty($models)) {
            $file->addFullyQualifiedName(new FullyQualifiedName(ModelException::class));
            $getBody = '        switch ($key) {';
            foreach ($models as $value) {
                $getBody .= "\n            case '" . $value . "':\n" . '                return $this->' . $value . ";";
            }
            $getBody .= "\n" . '            default:' . "\n" . '                throw new ModelException("array of objects $key doesn\'t exist");' . "\n" . '        }';
            $getArgument1 = new Argument('mixed', 'key');
            $get = new Method('get');
            $get->addArgument($getArgument1)->setBody($getBody);
            $phpDoc = new MethodPhpdoc();
            $phpDoc->setDescription(new Description('return the children by given key'));
            $phpDoc->addParameterTag(new ParameterTag('string', 'key'));
            $phpDoc->setReturnTag(new ReturnTag('AbstractModel[]'));
            $get->setPhpdoc($phpDoc);
            $object->addMethod($get);
        }
        $file->setStructure($object);
        $prettyPrinter = Build::prettyPrinter();
        $generatedCode = $prettyPrinter->generateCode($file);
        $fs = new Filesystem();

        $fs->dumpFile($fullPath, $generatedCode);
        $fs->dumpFile($fullPathYML, Yaml::dump($ymlConfig, 6));

        $question = new ConfirmationQuestion('Do you want to create an additional repository class?[Y/N]: ', false);
        if (!$helper->ask($input, $output, $question)) {
            return;
        }

        $command = $this->getApplication()->find('webuntis:generate:repository');
        $arguments = [
            'pathToModelYML' => $fullPathYML
        ];

        $argumentInput = new ArrayInput($arguments);

        $command->run($argumentInput, $output);
    }
}