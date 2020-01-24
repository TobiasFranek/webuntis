<?php
declare(strict_types=1);

namespace Webuntis\Tests\Models;

use PHPUnit\Framework\TestCase;
use Webuntis\Models\Classes;

/**
 * ClassesTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class ClassesTest extends TestCase
{
    public function testCreate() : void
    {   
        $data = [
            'id' => 1,
            'name' => 'test',
            'longName' => 'teststring'
        ];

        $classes = new Classes($data);

        $this->assertEquals($classes->getAttributes(), $data);

        $this->assertEquals(1, $classes->getId());
        $this->assertEquals('test', $classes->getName());
        $this->assertEquals('teststring', $classes->getFullName());

        $data['fullName'] = 'teststring';
        unset($data['longName']);

        $this->assertEquals($data, $classes->serialize());
        $this->assertEquals(json_encode($data), $classes->serialize('json'));
    }
}
