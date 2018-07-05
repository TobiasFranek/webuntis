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
 * fetches different Models by the given ids and maps it to the given field
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class ModelCollectionType implements TypeInterface {

    /**
     * executes an certain parsing part
     * @param AbstractModel $model
     * @param array $data
     * @param array $field
     */
    public static function execute(AbstractModel &$model, array $data, array $field) : void 
    {
        $fieldName = array_keys($field)[0];
        $fieldValues = $field[$fieldName];
        $query = new Query();
        $tmp = [];
        if (isset($data[$fieldValues['api']['name']])) {
            if (!empty($data[$fieldValues['api']['name']])) {
                foreach ($data[$fieldValues['api']['name']] as $value) {
                    if(isset($value[$fieldValues['api']['searchkey']])){
                        $referencedModel = $query->get($fieldValues['model']['name'])->findBy([$fieldValues['model']['searchkey'] => $value[$fieldValues['api']['searchkey']]]);
                    }else {
                        $referencedModel = $query->get($fieldValues['model']['name'])->findBy([$fieldValues['model']['searchkey'] => $value]);
                    }
                    if(isset($referencedModel[0])){
                        $tmp[] = $referencedModel[0];
                    }
                }
                $model->set($fieldName, $tmp);
            }
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
        $question = new Question('key that should be searched for in the API data array: ');
        $searchkey = $helper->ask($input, $output, $question);
        $question = new Question('Model name: ');
        $modelName = $helper->ask($input, $output, $question);
        $question = new Question('key that should be searched for in the Model (the searchkey from the api should somehow be connected): ');
        $modelSearchkey = $helper->ask($input, $output, $question);
        return [
            'type' => self::getName(),
            'api' => [
                'name' => $name,
                'searchkey' => $searchkey
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
        return 'modelCollection';
    }

    /**
     * return type of the Type Class
     * @return string
     */
    public static function getType() : string 
    {
        return '\\' . AbstractModel::class . '[]';
    }
}