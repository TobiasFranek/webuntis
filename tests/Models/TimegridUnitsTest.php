<?php
declare(strict_types=1);

namespace Webuntis\Tests\Models;

use PHPUnit\Framework\TestCase;
use Webuntis\Models\TimegridUnits;

/**
 * TimegridUnitsTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class TimegridUnitsTest extends TestCase
{
    public function testCreate() : void
    {   
        $data = [
            'id' => 1,
            'day' => 0,
            'timeUnits' => [
                'test' => 'test'
            ]
        ];

        $timeGridUnits = new TimegridUnits($data);

        $this->assertEquals($timeGridUnits->getAttributes(), $data);

        $this->assertEquals(1, $timeGridUnits->getId());
        $this->assertEquals(0, $timeGridUnits->getDay());
        $this->assertEquals([
            'test' => 'test'
        ], $timeGridUnits->getTimeUnits());

        $expected = [
            'id' => 1,
            'day' => 0,
            'timeUnits' => [
                'test' => 'test'
            ]
        ];

        $this->assertEquals($expected, $timeGridUnits->serialize());
    }
}
