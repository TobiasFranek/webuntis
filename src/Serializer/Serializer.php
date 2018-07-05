<?php
declare(strict_types=1);

namespace Webuntis\Serializer;

use JMS\Serializer\SerializerBuilder;

/**
 * Serializes givne data mostly Models
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class Serializer {

    static private $serializer;

    private function __construct() {}

    /**
     * serializes the given data with jms
     * added additional php array function
     * @param $data
     * @param $format
     * @return mixed|string
     */
    public static function serialize($data, string $format = null) {
        if(!$format) {
            return json_decode(self::getSerializer()->serialize($data, 'json'), true);
        }else {
            return self::getSerializer()->serialize($data, $format);
        }
    }

    /**
     * return a serializer
     * @return \JMS\Serializer\Serializer
     */
    private static function getSerializer() : object 
    {
        if(!self::$serializer) {
            self::$serializer = SerializerBuilder::create()->build();
            return self::$serializer;
        }else {
            return self::$serializer;
        }
    }
}