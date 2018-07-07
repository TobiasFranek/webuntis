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
            'surname' => 'Schidt'
        ];

        $exam = new ClassRegEvents($data);
        $this->assertEquals(1, $exam->getId());
        $this->assertEquals(2, $exam->getStudent()->getId());
        $this->assertEquals(new \DateTime('2018-07-06'), $exam->getDate());
        $this->assertEquals('testsubject', $exam->getSubject());
        $this->assertEquals('testreason', $exam->getReason());
        $this->assertEquals('eats during lesson', $exam->getText());

        $expected = [
            'id' => 1,
            'date' => '2018-07-06T00:00:00+0200',
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
            'text' => 'eats during lesson'
        ];

        $this->assertEquals($expected, $exam->serialize());
    }
}
