<?php
declare(strict_types=1);

namespace Webuntis\Tests\Models;

use PHPUnit\Framework\TestCase;
use Webuntis\Models\Period;
use Webuntis\Models\Teachers;
use Webuntis\Models\Students;
use Webuntis\Models\Classes;
use Webuntis\Models\Subjects;
use Webuntis\Configuration\WebuntisConfiguration;

final class PeriodTest extends TestCase
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
            'security_manager' => 'Webuntis\Tests\TestSecurityManager'
        ]);
    }

    public function testCreate() : void
    {   
        $data = [
            'id' => 1,
            'startTime' => '800',
            'endTime' => '850',
            'date' => '20180706',
            'kl' => [
                [
                    'id' => 1
                ]
            ],
            'te' => [
                [
                    'id' => 1
                ]
            ],
            'ro' => [
                [
                    'id' => 1
                ]
            ],
            'su' => [
                [
                    'id' => 1
                ]
            ]
        ];

        $period = new Period($data);
        $this->assertEquals(1, $period->getId());
        $this->assertEquals(1, $period->getSubjects()[0]->getId());
        $this->assertEquals(new \DateTime('2018-07-06 8:00'), $period->getStartTime());
        $this->assertEquals(new \DateTime('2018-07-06 8:50'), $period->getEndTime());
        $this->assertEquals(1, $period->getTeachers()[0]->getId());
        $this->assertEquals(1, $period->getClasses()[0]->getId());
        $this->assertEquals(1, $period->getRooms()[0]->getId());

        $expected = [
            'id' => 1,
            'startTime' => '2018-07-06T08:00:00+0200',
            'endTime' => '2018-07-06T08:50:00+0200',
            'classes' => [
                [
                    'id' => 1,
                    'name' => 'test',
                    'fullName' => 'teststring'
                ]
            ],
            'teachers' => [
                [
                    'id' => 1,
                    'name' => 'asdman',
                    'firstName' => 'asd',
                    'lastName' => 'man'
                ]
            ],
            'rooms' => [
                [
                    'id' => 1,
                    'name' => '210',
                    'fullName' => 'Second Floor'
                ]
            ],
            'subjects' => [
                [
                    'id' => 1,
                    'name' => 'en',
                    'fullName' => 'english'
                ]
            ],
            'code' => 'normal',
            'type' => 'lesson'
        ];

        $this->assertEquals($expected, $period->serialize());
    }
}
