<?php
declare(strict_types=1);

namespace Webuntis\Tests\Models;

use PHPUnit\Framework\TestCase;
use Webuntis\Models\ClassRegEvents;
use Webuntis\Configuration\WebuntisConfiguration;

/**
 * ClassRegEventsTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class ClassRegEventsTest extends TestCase
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
            'date' => '20180706',
            'studentid' => 2,
            'subject' => 'testsubject',
            'reason' => 'testreason',
            'text' => 'eats during lesson',
            'surname' => 'Schidt',
            'categoryId' => 1
        ];

        $classRegEvent = new ClassRegEvents($data);

        $this->assertEquals($classRegEvent->getAttributes(), $data);

        $this->assertEquals(1, $classRegEvent->getId());
        $this->assertEquals(2, $classRegEvent->getStudent()->getId());
        $this->assertEquals(1, $classRegEvent->getCategory()->getId());
        $this->assertEquals(new \DateTime('2018-07-06'), $classRegEvent->getDate());
        $this->assertEquals('testsubject', $classRegEvent->getSubject());
        $this->assertEquals('testreason', $classRegEvent->getReason());
        $this->assertEquals('eats during lesson', $classRegEvent->getText());

        $serialized = $classRegEvent->serialize();

        $serialized['date'] = substr($serialized['date'], 0, 19);

        $expected = [
            'id' => 1,
            'date' => '2018-07-06T00:00:00',
            'student' => [
                'id' => 2,
                'key' => '1234567',
                'name' => 'SchmidAme',
                'firstName' => 'Amelie',
                'lastName' => 'Schidt',
                'gender' => 'female'
            ],
            'subject' => 'testsubject',
            'reason' => 'testreason',
            'text' => 'eats during lesson',
            'category' => [
                'id' => 1,
                'name' => 'Disziplinarblatt #1',
                'fullName' => 'Disziplinarblatt #1',
                'group' => [
                    'id' => 1,
                    'name' => 'Group1'
                ]
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

        $expected = [
            'id' => 1,
            'date' => '2018-07-06T00:00:00',
            'student' => 'Schidt',
            'subject' => 'testsubject',
            'reason' => 'testreason',
            'text' => 'eats during lesson',
            'category' => 1
        ];

        $classRegEvent = new ClassRegEvents($data);

        $serialized = $classRegEvent->serialize();

        $serialized['date'] = substr($serialized['date'], 0, 19);

        $this->assertEquals($expected, $serialized);
    }
}
