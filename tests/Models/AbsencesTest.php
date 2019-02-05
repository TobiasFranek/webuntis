<?php
declare(strict_types=1);

namespace Webuntis\Tests\Models;

use PHPUnit\Framework\TestCase;
use Webuntis\Models\Period;
use Webuntis\Models\Teachers;
use Webuntis\Models\Students;
use Webuntis\Models\Absences;
use Webuntis\Configuration\WebuntisConfiguration;

/**
 * AbsencesTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class AbsencesTest extends TestCase
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
            'studentId' => 1234567,
            'subjectId' => 1,
            'teacherIds' => [
                '1'
            ],
            'studentGroup' => '5BHWII',
            'absenceReason' => 'Illness',
            'absentTime' => 50,
            'user' => 'admin',
            'checked' => true,
        ];

        $absence = new Absences($data);

        $this->assertEquals($absence->getAttributes(), $data);

        $this->assertEquals(1, $absence->getId());
        $this->assertEquals(1, $absence->getStudent()->getId());
        $this->assertEquals(new \DateTime('2018-07-06 8:00'), $absence->getStartTime());
        $this->assertEquals(new \DateTime('2018-07-06 8:50'), $absence->getEndTime());
        $this->assertEquals(1, $absence->getTeachers()[0]->getId());
        $this->assertEquals('5BHWII', $absence->getStudentGroup());
        $this->assertEquals('Illness', $absence->getAbsenceReason());
        $this->assertEquals(50, $absence->getAbsentTime());
        $this->assertEquals('admin', $absence->getUser());
        $this->assertEquals(true, $absence->getChecked());

        $serialized = $absence->serialize();

        $serialized['startTime'] = substr($serialized['startTime'], 0, 19);
        $serialized['endTime'] = substr($serialized['endTime'], 0, 19);
        $serialized['subject']['startTime'] = substr($serialized['subject']['startTime'], 0, 19);
        $serialized['subject']['endTime'] = substr($serialized['subject']['endTime'], 0, 19);

        $expected = [
            'id' => 1,
            'startTime' => '2018-07-06T08:00:00',
            'endTime' => '2018-07-06T08:50:00',
            'teachers' => [
                [
                    'id' => 1,
                    'name' => 'asdman',
                    'firstName' => 'asd',
                    'lastName' => 'man'
                ]
            ],
            'student' => [
                'id' => 1,
                'key' => 1234567,
                'name' => 'MüllerAle',
                'firstName' => 'Alexander',
                'lastName' => 'Müller',
                'gender' => 'male'
            ],
            'subject' => [
                'id' => 1,
                'startTime' => '2018-07-03T08:00:00',
                'endTime' => '2018-07-03T08:50:00',
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
                'subjects' => [
                    [
                        'id' => 1,
                        'name' => 'en',
                        'fullName' => 'english'
                    ]
                ],
                'rooms' => [
                    [
                        'id' => 1,
                        'name' => '210',
                        'fullName' => 'Second Floor'
                    ]
                ],
                'code' => 'normal',
                'type' => 'lesson'
            ],
            'studentGroup' => '5BHWII',
            'absenceReason' => 'Illness',
            'absentTime' => 50,
            'user' => 'admin',
            'checked' => true,
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

        $absence = new Absences($data);
        
        $oldExpected = $expected;
        $serialized = $absence->serialize();

        $serialized['startTime'] = substr($serialized['startTime'], 0, 19);
        $serialized['endTime'] = substr($serialized['endTime'], 0, 19);

        $expected = [
            'id' => 1,
            'startTime' => '2018-07-06T08:00:00',
            'endTime' => '2018-07-06T08:50:00',
            'teachers' => [
                1
            ],
            'student' => 1234567,
            'subject' => 1,
            'studentGroup' => '5BHWII',
            'absenceReason' => 'Illness',
            'absentTime' => 50,
            'user' => 'admin',
            'checked' => true,
        ];

        $this->assertEquals($expected, $serialized);


        $config = new WebuntisConfiguration([ 
            'default' => [
                   //f.e. thalia, cissa etc.
                    'server' => 'yourserver',
                    'school' => 'yourschool',
                    'username' => 'yourusername',
                    'password' => 'yourpassword',
                    'ignore_children' => false
                ],
            'admin' => [
                   //f.e. thalia, cissa etc.
                    'server' => 'yourserver',
                    'school' => 'yourschool',
                    'username' => 'youradminusername',
                    'password' => 'youradminpassword',
                    'ignore_children' => false
            ],
            'security_manager' => 'Webuntis\Tests\Util\TestSecurityManager'
        ]);

        $absence = new Absences($data);
        $serialized = $absence->load()->serialize();

        $serialized['startTime'] = substr($serialized['startTime'], 0, 19);
        $serialized['endTime'] = substr($serialized['endTime'], 0, 19);
        $serialized['subject']['startTime'] = substr($serialized['subject']['startTime'], 0, 19);
        $serialized['subject']['endTime'] = substr($serialized['subject']['endTime'], 0, 19);

        $this->assertEquals($serialized, $oldExpected);
    }
}
