<?php
declare(strict_types=1);

namespace Webuntis\Tests\Types;

use PHPUnit\Framework\TestCase;
use Webuntis\Repositories\UserRepository;
use Webuntis\Models\AbstractModel;
use Webuntis\Types\BoolType;
use Webuntis\Handler\ExecutionHandler;
use Webuntis\Configuration\WebuntisConfiguration;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Helper\QuestionHelper;
use Webuntis\Exceptions\TypeException;

/**
 * BoolTypeTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class BoolTypeTest extends TestCase
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
            'boolTest' => [
                'type' => 'bool',
                'api' => [
                    'name' => 'boolTest'
                ]
            ]
        ];
        $data = [
            'boolTest' => true
        ];

        $test = new class extends AbstractModel {
            private $boolTest;

            public function set(string $field, $value) 
            {
                $this->$field = $value;
            }

            public function getBoolTest() 
            {
                return $this->boolTest;
            }
        };

        BoolType::execute($test, $data, $field);

        $this->assertEquals(true, $test->getBoolTest());

        $data = [
            'boolTest' => 'true'
        ];

        BoolType::execute($test, $data, $field);

        $this->assertEquals(true, $test->getBoolTest());

        $data = [
            'boolTest' => null
        ];
        
        BoolType::execute($test, $data, $field);

        $this->assertEquals(false, $test->getBoolTest());
    }

    public function testGenerateTypeWithConsole() : void
    {
        $output = $this->createMock(Output::class);
        $input = $this->createMock(Input::class);
        $helper = $this->createMock(QuestionHelper::class);

        $helper->method('ask')
               ->willReturn('test');
        
        $this->assertEquals([
            'type' => 'bool',
            'api' => [
                'name' => 'test'
            ]
        ], BoolType::generateTypeWithConsole($output, $input, $helper));
    }

    public function testGetName() : void 
    {
        $this->assertEquals('bool', BoolType::getName());
    }

    public function testGetType() : void 
    {
        $this->assertEquals('bool', BoolType::getType());
    }
}
