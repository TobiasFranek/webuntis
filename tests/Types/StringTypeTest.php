<?php
declare(strict_types=1);

namespace Webuntis\Tests\Types;

use PHPUnit\Framework\TestCase;
use Webuntis\Repositories\UserRepository;
use Webuntis\Models\Holidays;
use Webuntis\Models\AbstractModel;
use Webuntis\Types\StringType;
use Webuntis\Handler\ExecutionHandler;
use Webuntis\Configuration\WebuntisConfiguration;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Helper\QuestionHelper;


final class StringTypeTest extends TestCase
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
            'stringTest' => [
                'type' => 'string',
                'api' => [
                    'name' => 'testString'
                ]
            ]
        ];
        $data = [
            'testString' => 'hello'
        ];

        $test = new class extends AbstractModel {
            private $stringTest;

            public function set(string $field, $value) 
            {
                $this->$field = $value;
            }

            public function getStringTest() 
            {
                return $this->stringTest;
            }
        };

        StringType::execute($test, $data, $field);

        $this->assertEquals('hello', $test->getStringTest());
    }

    public function testGenerateTypeWithConsole() : void
    {
        $output = $this->createMock(Output::class);
        $input = $this->createMock(Input::class);
        $helper = $this->createMock(QuestionHelper::class);

        $helper->method('ask')
               ->willReturn('test');
        
        $this->assertEquals([
            'type' => 'string',
            'api' => [
                'name' => 'test'
            ]
        ], StringType::generateTypeWithConsole($output, $input, $helper));
    }

    public function testGetName() : void 
    {
        $this->assertEquals('string', StringType::getName());
    }

    public function testGetType() : void 
    {
        $this->assertEquals('string', StringType::getType());
    }
}
