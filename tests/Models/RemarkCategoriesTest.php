<?php
declare(strict_types=1);

namespace Webuntis\Tests\Models;

use PHPUnit\Framework\TestCase;
use Webuntis\Models\RemarkCategories;

/**
 * RemarkCategoriesTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class RemarkCategoriesTest extends TestCase
{
    public function testCreate() : void
    {   
        $data = [
            'id' => 1,
            'name' => 'test',
            'longName' => 'teststring'
        ];

        $remarkCategories = new RemarkCategories($data);

        $this->assertEquals($remarkCategories->getAttributes(), $data);

        $this->assertEquals(1, $remarkCategories->getId());
        $this->assertEquals('test', $remarkCategories->getName());
        $this->assertEquals('teststring', $remarkCategories->getFullName());

        $data['fullName'] = 'teststring';
        unset($data['longName']);

        $this->assertEquals($data, $remarkCategories->serialize());
        $this->assertEquals(json_encode($data), $remarkCategories->serialize('json'));
    }
}
