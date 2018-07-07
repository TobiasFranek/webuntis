<?php
declare(strict_types=1);

namespace Webuntis\Tests\Models;

use PHPUnit\Framework\TestCase;
use Webuntis\Models\StatusData;

/**
 * StatusDataTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class StatusDataTest extends TestCase
{
    public function testCreate() : void
    {   
        $data = [
            'id' => 1,
            'lstypes' => [
                'test' => 'test'
            ],
            'codes' => [
                'test' => 'test'
            ]
        ];

        $classes = new StatusData($data);

        $this->assertEquals(1, $classes->getId());
        $this->assertEquals([
            'test' => 'test'
        ], $classes->getLessonTypes());
        $this->assertEquals([
            'test' => 'test'
        ], $classes->getCodes());

        $expected = [
            'id' => 1,
            'lessonTypes' => [
                'test' => 'test'
            ],
            'codes' => [
                'test' => 'test'
            ]
        ];

        $this->assertEquals($expected, $classes->serialize());
    }
}
