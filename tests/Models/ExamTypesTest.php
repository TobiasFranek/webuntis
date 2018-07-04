<?php
declare(strict_types=1);

namespace Webuntis\Tests\Models;

use PHPUnit\Framework\TestCase;
use Webuntis\Models\ExamTypes;

final class ExamTypesTest extends TestCase
{
    public function testCreate() : void
    {   
        $data = [
            'id' => 1,
            'name' => 'test',
            'longName' => 'teststring',
        ];

        $examTypes = new ExamTypes($data);
        $this->assertEquals(1, $examTypes->getId());
        $this->assertEquals('test', $examTypes->getName());
        $this->assertEquals('teststring', $examTypes->getLongName());

        $expected = [
            'id' => 1,
            'type' => 1,
            'name' => 'test',
            'longName' => 'teststring'
        ];

        $this->assertEquals($expected, $examTypes->serialize());
        $this->assertEquals(json_encode($expected), $examTypes->serialize('json'));
    }
}
