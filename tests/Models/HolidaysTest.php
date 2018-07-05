<?php
declare(strict_types=1);

namespace Webuntis\Tests\Models;

use PHPUnit\Framework\TestCase;
use Webuntis\Models\Holidays;
use Webuntis\Models\Teachers;
use Webuntis\Configuration\WebuntisConfiguration;

/**
 * HolidaysTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class HolidaysTest extends TestCase
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
            'security_manager' => 'Webuntis\Tests\Util\TestSecurityManager'
        ]);
    }

    public function testCreate() : void
    {   
        $data = [
            'id' => 1,
            'name' => 'test',
            'longName' => 'teststring',
            'startDate' => '20180704',
            'endDate' => '20180706'
        ];

        $holiday = new Holidays($data);

        $this->assertEquals(1, $holiday->getId());
        $this->assertEquals('test', $holiday->getName());
        $this->assertEquals('teststring', $holiday->getFullName());
        $this->assertEquals(new \DateTime('2018-07-04'), $holiday->getStartDate());
        $this->assertEquals(new \DateTime('2018-07-06'), $holiday->getEndDate());

        $expected = [
            'id' => 1,
            'name' => 'test',
            'fullName' => 'teststring',
            'startDate' => '2018-07-04T00:00:00+0200',
            'endDate' => '2018-07-06T00:00:00+0200'
        ];

        $this->assertEquals($expected, $holiday->serialize());
        $this->assertEquals(json_encode($expected), $holiday->serialize('json'));
    }
}
