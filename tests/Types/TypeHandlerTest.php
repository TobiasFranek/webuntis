<?php
declare(strict_types=1);

namespace Webuntis\Tests\Types;

use PHPUnit\Framework\TestCase;
use Webuntis\Repositories\UserRepository;
use Webuntis\Models\Holidays;
use Webuntis\Models\AbstractModel;
use Webuntis\Types\TypeHandler;
use Webuntis\Handler\ExecutionHandler;
use Webuntis\Configuration\WebuntisConfiguration;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Helper\QuestionHelper;

/**
 * TypeHandlerTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class TypeHandlerTest extends TestCase
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

    public function testhandle() : void
    {   
        $field = [
            'intTest' => [
                'type' => 'int',
                'api' => [
                    'name' => 'testInt'
                ]
            ],
            'stringTest' => [
                'type' => 'string',
                'api' => [
                    'name' => 'testString'
                ]
            ],
            'dateTest' => [
                'type' => 'date',
                'api' => [
                    'name' => 'testDate'
                ]
            ]
        ];
        $data = [
            'testInt' => '22',
            'testString' => 'hello',
            'testDate' => '20180704'
        ];

        $test = new class extends AbstractModel {
            private $intTest;
            private $stringTest;
            private $dateTest;

            public function set(string $field, $value) 
            {
                $this->$field = $value;
            }

            public function getIntTest() 
            {
                return $this->intTest;
            }

            public function getStringTest()
            {
                return $this->stringTest;
            }

            public function getDateTest()
            {
                return $this->dateTest;
            }
        };

        $typeHandler = new TypeHandler();
        
        $typeHandler->handle($test, $data, $field);

        $this->assertEquals(22, $test->getIntTest());
        $this->assertEquals('hello', $test->getStringTest());
        $this->assertEquals(new \DateTime('2018-07-04'), $test->getDateTest());
    }

    public function testGetAllTypes() : void 
    {
        $array = TypeHandler::getAllTypes();

        $this->assertArrayHasKey('int', $array);
        $this->assertArrayHasKey('string', $array);
        $this->assertArrayHasKey('bool', $array);
        $this->assertArrayHasKey('model', $array);
        $this->assertArrayHasKey('modelCollection', $array);
        $this->assertArrayHasKey('date', $array);
        $this->assertArrayHasKey('mergeTimeAndDate', $array);
    }
}
