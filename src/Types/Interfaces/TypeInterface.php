<?php
declare(strict_types=1);

namespace Webuntis\Types\Interfaces;


use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webuntis\Models\AbstractModel;

/**
 * Interface for the different Types
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
interface TypeInterface {

    /**
     * returns the name of the Type
     * @return string
     */
    public static function getName() : string;

    /**
     * returns type of the class
     * @return string
     */
    public static function getType() : string;

    /**
     * executes an certain parsing part
     * @param object $model
     * @param array $data
     * @param array $field
     */
    public static function execute(object &$model, array $data, array $field);

    /**
     * asks for the params according to the type and return an array with the field information
     * @param OutputInterface $output
     * @param InputInterface $input
     * @param $helper
     * @return array
     */
    public static function generateTypeWithConsole(OutputInterface $output, InputInterface $input, $helper) : array;
}