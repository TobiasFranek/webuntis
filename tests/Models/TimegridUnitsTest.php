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

        $classes = new TimegridUnits($data);

        $this->assertEquals(1, $classes->getId());
        $this->assertEquals(0, $classes->getDay());
        $this->assertEquals([
            'test' => 'test'
        ], $classes->getTimeUnits());

        $expected = [
            'id' => 1,
            'day' => 0,
            'timeUnits' => [
                'test' => 'test'
            ]
        ];

        $this->assertEquals($expected, $classes->serialize());
    }
}
