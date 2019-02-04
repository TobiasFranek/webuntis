<?php
declare(strict_types=1);

namespace Webuntis\Tests\Models;

use PHPUnit\Framework\TestCase;
use Webuntis\Models\LastImportTime;

/**
 * LastImportTImeTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class LastImportTimeTest extends TestCase
{
    public function testCreate() : void
    {   
        $data = [
            'id' => 1,
            'time' => 22222222
        ];

        $importTime = new LastImportTime($data);

        $this->assertEquals($importTime->getAttributes(), $data);

        $this->assertEquals(1, $importTime->getId());
        $this->assertEquals(22222222, $importTime->getTime());

        $this->assertEquals(['id' => 1, 'time' => 22222222], $importTime->serialize());
    }
}
