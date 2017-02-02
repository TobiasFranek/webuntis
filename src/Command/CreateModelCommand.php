<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace Webuntis\Command;


use Memio\Memio\Config\Build;
use Memio\Model\Argument;
use Memio\Model\Constant;
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
 * Class CreateModelCommand
 * @package Webuntis\Command
 * @author Tobias Franek <tobias.franek@gmail.com>
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
                    if($attribute == 'model' || $attribute == 'modelCollection') {
                        $models[] = $name;
                    }
                } else {
                    $output->writeln('<error>Type does not exist</error>');
                }
            }
            $question = new Question('Name of the attribute you want to add [enter if finished]: ');
            $name = $helper->ask($input, $output, $question);
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

        if(!empty($models)) {
            $file->addFullyQualifiedName(new FullyQualifiedName(ModelException::class));
            $getBody = '        switch ($key) {';
            foreach($models as $value) {
                $getBody .= "\n            case '" . $value . "':\n" . '                return $this->' . $value . ";";
            }
            $getBody .= "\n" . '            default:' . "\n" . '                throw new ModelException("array of objects $key doesn\'t exist");' . "\n" . '        }';
            $getArgument1 = Argument::make('mixed', 'key');
            $get = Method::make('get')->addArgument($getArgument1)->setBody($getBody);
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

        $returnCode = $command->run($argumentInput, $output);
    }
}