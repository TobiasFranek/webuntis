<?php
declare(strict_types=1);

namespace Webuntis\Tests\Models;

use PHPUnit\Framework\TestCase;
use Webuntis\Models\Departments;

final class DepartmentsTest extends TestCase
{
    public function testDepartments() : void
    {   
        $data = [
            'id' => 1,
            'name' => 'test',
            'longName' => 'teststring'
        ];

        $departments = new Departments($data);

        $this->assertEquals(1, $departments->getId());
        $this->assertEquals('test', $departments->getName());
        $this->assertEquals('teststring', $departments->getFullName());

        $data['fullName'] = 'teststring';
        unset($data['longName']);

        $this->assertEquals($data, $departments->serialize());
        $this->assertEquals(json_encode($data), $departments->serialize('json'));
    }
}
