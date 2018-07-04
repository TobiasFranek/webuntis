<?php
declare(strict_types=1);

namespace Webuntis\Tests\Models;

use PHPUnit\Framework\TestCase;
use Webuntis\Models\Students;

final class StudentsTest extends TestCase
{
    public function testCreate() : void
    {   
        $data = [
            'id' => 1,
            'name' => 'john.doe',
            'foreName' => 'john',
            'longName' => 'doe',
            'key' => '203145',
            'gender' => 'male'
        ];

        $student = new Students($data);

        $this->assertEquals(1, $student->getId());
        $this->assertEquals('john.doe', $student->getName());
        $this->assertEquals('john', $student->getFirstName());
        $this->assertEquals('doe', $student->getLastName());
        $this->assertEquals('203145', $student->getKey());
        $this->assertEquals('male', $student->getGender());

        $expected = [
            'id' => 1,
            'name' => 'john.doe',
            'firstName' => 'john',
            'lastName' => 'doe',
            'key' => '203145',
            'gender' => 'male'
        ];

        $this->assertEquals($expected, $student->serialize());
    }
}
