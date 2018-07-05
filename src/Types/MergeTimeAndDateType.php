<?php
declare(strict_types=1);

namespace Webuntis\Types;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Webuntis\Models\AbstractModel;
use Webuntis\Types\Interfaces\TypeInterface;

/**
 * merges an date and time value to create one datetime object and writes it to one field
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class MergeTimeAndDateType implements TypeInterface {

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
        if (isset($data[$fieldValues['api']['time']])) {
            if (strlen(strval($data[$fieldValues['api']['time']])) < 4) {
                $model->set($fieldName, new \DateTime($data[$fieldValues['api']['date']] . ' ' . '0' . substr(strval($data[$fieldValues['api']['time']]), 0, 1) . ':' . substr(strval($data[$fieldValues['api']['time']]), strlen(strval($data[$fieldValues['api']['time']])) - 2, strlen(strval($data[$fieldValues['api']['time']])))));
            } else {
                $model->set($fieldName, new \DateTime($data[$fieldValues['api']['date']] . ' ' . substr(strval($data[$fieldValues['api']['time']]), 0, 2) . ':' . substr(strval($data[$fieldValues['api']['time']]), strlen(strval($data[$fieldValues['api']['time']])) - 2, strlen(strval($data[$fieldValues['api']['time']])))));
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
        $question = new Question('API key for the time: ');
        $time = $helper->ask($input, $output, $question);
        $question = new Question('API key for the date: ');
        $date = $helper->ask($input, $output, $question);
        return [
            'type' => self::getName(),
            'api' => [
                'time' => $time,
                'date' => $date
            ]
        ];
    }

    /**
     * return name of the type
     * @return string
     */
    public static function getName() : string 
    {
        return 'mergeTimeAndDate';
    }

    /**
     * return type of the Type Class
     * @return string
     */
    public static function getType() : string 
    {
        return \DateTime::class;
    }
}