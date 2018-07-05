<?php
declare(strict_types=1);

namespace Webuntis\Tests\Models;

use PHPUnit\Framework\TestCase;
use Webuntis\Models\Teachers;

final class TeachersTest extends TestCase
{
    public function testCreate() : void
    {   
        $data = [
            'id' => 1,
            'name' => 'john.doe',
            'foreName' => 'john',
            'longName' => 'doe',
        ];

        $teacher = new Teachers($data);

        $this->assertEquals(1, $teacher->getId());
        $this->assertEquals('john.doe', $teacher->getName());
        $this->assertEquals('john', $teacher->getFirstName());
        $this->assertEquals('doe', $teacher->getLastName());

        $expected = [
            'id' => 1,
            'name' => 'john.doe',
            'firstName' => 'john',
            'lastName' => 'doe',
        ];

        $this->assertEquals($expected, $teacher->serialize());
    }
}
