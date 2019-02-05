<?php
declare(strict_types=1);

namespace Webuntis\Tests\Models;

use PHPUnit\Framework\TestCase;
use Webuntis\Models\Exams;
use Webuntis\Models\Teachers;
use Webuntis\Models\Students;
use Webuntis\Models\Classes;
use Webuntis\Models\Subjects;
use Webuntis\Configuration\WebuntisConfiguration;

/**
 * ExamsTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class ExamsTest extends TestCase
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
            'startTime' => '800',
            'endTime' => '850',
            'date' => '20180706',
            'classes' => [
                [
                    'id' => 1
                ]
            ],
            'teachers' => [
                [
                    'id' => 1
                ]
            ],
            'students' => [
                [
                    'id' => 2
                ]
            ],
            'subject' => 1
        ];

        $exam = new Exams($data);

        $this->assertEquals($exam->getAttributes(), $data);

        $this->assertEquals(1, $exam->getId());
        $this->assertEquals(1, $exam->getSubject()->getId());
        $this->assertEquals(new \DateTime('2018-07-06 8:00'), $exam->getStartDate());
        $this->assertEquals(new \DateTime('2018-07-06 8:50'), $exam->getEndDate());
        $this->assertEquals(1, $exam->getTeachers()[0]->getId());
        $this->assertEquals(1, $exam->getClasses()[0]->getId());
        $this->assertEquals(2, $exam->getStudents()[0]->getId());

        $serialized = $exam->serialize();

        $serialized['startDate'] = substr($serialized['startDate'], 0, 19);
        $serialized['endDate'] = substr($serialized['endDate'], 0, 19);

        $expected = [
            'id' => 1,
            'startDate' => '2018-07-06T08:00:00',
            'endDate' => '2018-07-06T08:50:00',
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
            'students' => [
                [
                    'id' => 2,
                    'key' => '1234567',
                    'name' => 'SchmidAme',
                    'firstName' => 'Amelie',
                    'lastName' => 'Schidt',
                    'gender' => 'female'
                ]
            ],
            'subject' => [
                'id' => 1,
                'name' => 'en',
                'fullName' => 'english'
            ]
        ];

        $this->assertEquals($expected, $serialized);

        $config = new WebuntisConfiguration([ 
            'default' => [
                   //f.e. thalia, cissa etc.
                    'server' => 'yourserver',
                    'school' => 'yourschool',
                    'username' => 'yourusername',
                    'password' => 'yourpassword',
                    'ignore_children' => true
                ],
            'admin' => [
                   //f.e. thalia, cissa etc.
                    'server' => 'yourserver',
                    'school' => 'yourschool',
                    'username' => 'youradminusername',
                    'password' => 'youradminpassword',
                    'ignore_children' => true
            ],
            'security_manager' => 'Webuntis\Tests\Util\TestSecurityManager'
        ]);

        $exam = new Exams($data);

        $serialized = $exam->serialize();

        $serialized['startDate'] = substr($serialized['startDate'], 0, 19);
        $serialized['endDate'] = substr($serialized['endDate'], 0, 19);

        $expected = [
            'id' => 1,
            'startDate' => '2018-07-06T08:00:00',
            'endDate' => '2018-07-06T08:50:00',
            'classes' => [
                [
                    'id' => 1,
                ]
            ],
            'teachers' => [
                [
                    'id' => 1,
                ]
            ],
            'students' => [
                [
                    'id' => 2,
                ]
            ],
            'subject' => 1
        ];

        $this->assertEquals($expected, $serialized);
    }
}
