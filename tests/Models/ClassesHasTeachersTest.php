<?php
declare(strict_types=1);

namespace Webuntis\Tests\Models;

use PHPUnit\Framework\TestCase;
use Webuntis\Models\ClassHasTeachers;
use Webuntis\Configuration\WebuntisConfiguration;

final class ClassHasTeachersTest extends TestCase
{
    public function testClasses() : void
    {   
        $data = [
            'id' => 1,
            'name' => 'test',
            'fullName' => 'teststring',
            'teachers' => [
                'teacher'
            ]
        ];

        $classHasTeachers = new ClassHasTeachers();

        $classHasTeachers->setId(1);
        $classHasTeachers->setName('test');
        $classHasTeachers->setFullName('teststring');
        $classHasTeachers->setTeachers(['teacher']);

        $this->assertEquals(1, $classHasTeachers->getId());
        $this->assertEquals('test', $classHasTeachers->getName());
        $this->assertEquals('teststring', $classHasTeachers->getFullName());
        $this->assertEquals(['teacher'], $classHasTeachers->getTeachers());

        
        $this->assertEquals($data, $classHasTeachers->serialize());
        $this->assertEquals(json_encode($data), $classHasTeachers->serialize('json'));
    }
}
