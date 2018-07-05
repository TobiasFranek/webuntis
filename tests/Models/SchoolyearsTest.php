<?php
declare(strict_types=1);

namespace Webuntis\Tests\Models;

use PHPUnit\Framework\TestCase;
use Webuntis\Models\Schoolyears;
use Webuntis\Models\Teachers;
use Webuntis\Configuration\WebuntisConfiguration;

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

        $expected = [
            'id' => 1,
            'name' => 'test',
            'startDate' => '2018-07-04T00:00:00+0200',
            'endDate' => '2018-07-06T00:00:00+0200'
        ];

        $this->assertEquals($expected, $schoolyear->serialize());
        $this->assertEquals(json_encode($expected), $schoolyear->serialize('json'));
    }
}
