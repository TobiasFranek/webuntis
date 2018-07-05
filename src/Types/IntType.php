<?php
declare(strict_types=1);

namespace Webuntis\Types;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Webuntis\Models\AbstractModel;
use Webuntis\Types\Interfaces\TypeInterface;

/**
 * maps a int value to the according field
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class IntType implements TypeInterface {

    /**
     * executes an certain parsing part
     * @param AbstractModel $model
     * @param array $data
     * @param array $field
     */
    public static function execute(AbstractModel &$model, array $data, array $field) : void 
    {
        $fieldName = array_keys($field)[0];
        if(isset($data[$field[$fieldName]['api']['name']])) {
            $model->set($fieldName, intval($data[$field[$fieldName]['api']['name']]));
        }
    }

    /**
     * asks for the params according to the type and return an array with the field information
     * @param OutputInterface $output
     * @param InputInterface $input
     * @param $helper
     * @return array
     */
    public static function generateTypeWithConsole(OutputInterface $output, InputInterface $input, $helper) : array 
    {
        $question = new Question('API name: ');
        $apiName = $helper->ask($input, $output, $question);
        return [
            'type' => self::getName(),
            'api' => [
                'name' => $apiName
            ]
        ];
    }

    /**
     * return name of the type
     * @return string
     */
    public static function getName() : string 
    {
        return 'int';
    }

    /**
     * return type of the Type Class
     * @return string
     */
    public static function getType() : string 
    {
        return 'int';
    }
}