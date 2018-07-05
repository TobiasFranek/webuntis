<?php
declare(strict_types=1);

namespace Webuntis\Tests\Types;

use PHPUnit\Framework\TestCase;
use Webuntis\Repositories\UserRepository;
use Webuntis\Models\Holidays;
use Webuntis\Models\AbstractModel;
use Webuntis\Types\IntType;
use Webuntis\Handler\ExecutionHandler;
use Webuntis\Configuration\WebuntisConfiguration;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Helper\QuestionHelper;

/**
 * IntTypeTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class IntTypeTest extends TestCase
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

    public function testExecute() : void
    {   
        $field = [
            'intTest' => [
                'type' => 'int',
                'api' => [
                    'name' => 'testInt'
                ]
            ]
        ];
        $data = [
            'testInt' => '22'
        ];

        $test = new class extends AbstractModel {
            private $intTest;

            public function set(string $field, $value) 
            {
                $this->$field = $value;
            }

            public function getIntTest() 
            {
                return $this->intTest;
            }
        };

        IntType::execute($test, $data, $field);

        $this->assertEquals(22, $test->getIntTest());
    }

    public function testGenerateTypeWithConsole() : void
    {
        $output = $this->createMock(Output::class);
        $input = $this->createMock(Input::class);
        $helper = $this->createMock(QuestionHelper::class);

        $helper->method('ask')
               ->willReturn('test');
        
        $this->assertEquals([
            'type' => 'int',
            'api' => [
                'name' => 'test'
            ]
        ], IntType::generateTypeWithConsole($output, $input, $helper));
    }

    public function testGetName() : void 
    {
        $this->assertEquals('int', IntType::getName());
    }

    public function testGetType() : void 
    {
        $this->assertEquals('int', IntType::getType());
    }
}
