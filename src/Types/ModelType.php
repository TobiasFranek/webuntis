<?php
declare(strict_types=1);

namespace Webuntis\Types;


use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Webuntis\Models\AbstractModel;
use Webuntis\Query\Query;
use Webuntis\Types\Interfaces\TypeInterface;

/**
 * fetches one different Model by the given id and maps it to a given field
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class ModelType implements TypeInterface {

    /**
     * executes an certain parsing part
     * @param object $model
     * @param array $data
     * @param array $field
     */
    public static function execute(object &$model, array $data, array $field) : void 
    {
        $fieldName = array_keys($field)[0];
        $fieldValues = $field[$fieldName];
        $query = new Query();
        if(isset($data[$fieldValues['api']['name']])) {
            $model->set($fieldName, $query->get($fieldValues['model']['name'])->findBy([$fieldValues['model']['searchkey'] => $data[$fieldValues['api']['name']]])[0]);
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
        $question = new Question('API key for the data array: ');
        $name = $helper->ask($input, $output, $question);
        $question = new Question('Model name: ');
        $modelName = $helper->ask($input, $output, $question);
        $question = new Question('key that should be searched for in the Model (the searchkey from the api should somehow be connected): ');
        $modelSearchkey = $helper->ask($input, $output, $question);
        return [
            'type' => self::getName(),
            'api' => [
                'name' => $name,
            ],
            'model' => [
                'name' => $modelName,
                'searchkey' => $modelSearchkey
            ]
        ];
    }

    /**
     * return name of the type
     * @return string
     */
    public static function getName() : string 
    {
        return 'model';
    }

    /**
     * return type of the Type Class
     * @return string
     */
    public static function getType() : string 
    {
        return AbstractModel::class;
    }
}