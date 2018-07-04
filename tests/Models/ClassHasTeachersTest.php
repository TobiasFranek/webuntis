<?php
declare(strict_types=1);

namespace Webuntis\Tests\Models;

use PHPUnit\Framework\TestCase;
use Webuntis\Models\ClassHasTeachers;
use Webuntis\Models\Teachers;
use Webuntis\Configuration\WebuntisConfiguration;

final class ClassHasTeachersTest extends TestCase
{
    public function setUp() : void 
    {
        $config = new WebuntisConfiguration([ 
            'default' => [
                   //f.e. thalia, cissa etc.
                    'server' => 'yourserver',
                    'school' => 'yourschool',
                    'username' => 'yourusername',
                    'password' => 'yourpassword'
                ],
            'admin' => [
                   //f.e. thalia, cissa etc.
                    'server' => 'yourserver',
                    'school' => 'yourschool',
                    'username' => 'youradminusername',
                    'password' => 'youradminpassword'
            ],
            'security_manager' => 'Webuntis\Tests\TestSecurityManager'
        ]);
    }

    public function testCreate() : void
    {   
        $data = [
            'id' => 1,
            'name' => 'test',
            'fullName' => 'teststring',
            'teachers' => [
                'id' => 1
            ]
        ];

        $classHasTeachers = new ClassHasTeachers($data);

        $this->assertEquals(1, $classHasTeachers->getId());
        $this->assertEquals('test', $classHasTeachers->getName());
        $this->assertEquals('teststring', $classHasTeachers->getFullName());
        $this->assertInstanceOf(Teachers::class, $classHasTeachers->getTeachers()[0]);
        $this->assertEquals(1, $classHasTeachers->getTeachers()[0]->getId());

        $data['fullName'] = 'teststring';
        unset($data['longName']);

        $this->assertEquals([
            'id' => 1,
            'name' => 'test',
            'fullName' => 'teststring',
            'teachers' => [
                [
                    'id' => 1,
                    'name' => 'asdman',
                    'firstName' => 'asd',
                    'lastName' => 'man'
                ]
            ]
        ], $classHasTeachers->serialize());
        $this->assertEquals(json_encode([
            'id' => 1,
            'name' => 'test',
            'fullName' => 'teststring',
            'teachers' => [
                [
                    'id' => 1,
                    'name' => 'asdman',
                    'firstName' => 'asd',
                    'lastName' => 'man'
                ]
            ]
        ]), $classHasTeachers->serialize('json'));
    }
}
