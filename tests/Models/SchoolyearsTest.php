<?php
declare(strict_types=1);

namespace Webuntis\Tests\Models;

use PHPUnit\Framework\TestCase;
use Webuntis\Models\Schoolyears;
use Webuntis\Models\Teachers;
use Webuntis\Configuration\WebuntisConfiguration;

/**
 * SchoolyearsTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class SchoolyearsTest extends TestCase
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
            'startDate' => '20180704',
            'endDate' => '20180706'
        ];

        $schoolyear = new Schoolyears($data);

        $this->assertEquals(1, $schoolyear->getId());
        $this->assertEquals('test', $schoolyear->getName());
        $this->assertEquals(new \DateTime('2018-07-04'), $schoolyear->getStartDate());
        $this->assertEquals(new \DateTime('2018-07-06'), $schoolyear->getEndDate());

        $serialized = $schoolyear->serialize();

        $serialized['startDate'] = substr($serialized['startDate'], 0, 19);
        $serialized['endDate'] = substr($serialized['endDate'], 0, 19);

        $expected = [
            'id' => 1,
            'name' => 'test',
            'startDate' => '2018-07-04T00:00:00',
            'endDate' => '2018-07-06T00:00:00'
        ];

        $this->assertEquals($expected, $serialized);
    }
}
