<?php
declare(strict_types=1);

namespace Webuntis\Tests\Models;

use PHPUnit\Framework\TestCase;
use Webuntis\Models\Classes;

final class ClassesTest extends TestCase
{
    public function testClasses() : void
    {   
        $data = [
            'id' => 1,
            'name' => 'test',
            'longName' => 'teststring'
        ];

        $classes = new Classes($data);

        $this->assertEquals(1, $classes->getId());
        $this->assertEquals('test', $classes->getName());
        $this->assertEquals('teststring', $classes->getFullName());

        $data['fullName'] = 'teststring';
        unset($data['longName']);

        $this->assertEquals($data, $classes->serialize());
        $this->assertEquals(json_encode($data), $classes->serialize('json'));
    }
}
