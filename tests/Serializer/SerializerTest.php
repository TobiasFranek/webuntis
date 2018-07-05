<?php
declare(strict_types=1);

namespace Webuntis\Tests\Models;

use PHPUnit\Framework\TestCase;
use Webuntis\Serializer\Serializer;

final class SerializerTest extends TestCase
{
    public function testCreate() : void
    {   
        $toSerialize = new class {
            private $testString = 'test';
            private $testInt = 1;
        };

        $expected = [
            'test_string' => 'test',
            'test_int' => 1
        ];

        $expectedJson = '{"test_string":"test","test_int":1}';

        $this->assertEquals($expected, Serializer::serialize($toSerialize));
        $this->assertEquals($expectedJson, Serializer::serialize($toSerialize, 'json'));
    }   
}
