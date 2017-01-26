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

namespace Webuntis\Serializer;

use JMS\Serializer\SerializerBuilder;

/**
 * Class Serializer
 * @package Webuntis\Serializer
 * @author Tobias Franek <tobias.franek@gmail.com>
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
    public static function serialize($data, $format = null) {
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
    private static function getSerializer() {
        if(!self::$serializer) {
            self::$serializer = SerializerBuilder::create()->build();
            return self::$serializer;
        }else {
            return self::$serializer;
        }
    }
}