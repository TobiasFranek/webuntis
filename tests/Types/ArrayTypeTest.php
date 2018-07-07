<?php
declare(strict_types=1);

namespace Webuntis\Tests\Types;

use PHPUnit\Framework\TestCase;
use Webuntis\Repositories\UserRepository;
use Webuntis\Models\Holidays;
use Webuntis\Models\AbstractModel;
use Webuntis\Types\ArrayType;
use Webuntis\Handler\ExecutionHandler;
use Webuntis\Configuration\WebuntisConfiguration;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Helper\QuestionHelper;

/**
 * ArrayTypeTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class ArrayTypeTest extends TestCase
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
            'arrayTest' => [
                'type' => 'array',
                'api' => [
                    'name' => 'testArray'
                ]
            ]
        ];
        $data = [
            'testArray' => [
                'test' => 222,
                'asdasda'
            ]
        ];

        $test = new class extends AbstractModel {
            private $arrayTest;

            public function set(string $field, $value) 
            {
                $this->$field = $value;
            }

            public function getArrayTest() 
            {
                return $this->arrayTest;
            }
        };

        ArrayType::execute($test, $data, $field);

        $this->assertEquals([
            'test' => 222,
            'asdasda'
        ], $test->getArrayTest());
    }

    public function testGenerateTypeWithConsole() : void
    {
        $output = $this->createMock(Output::class);
        $input = $this->createMock(Input::class);
        $helper = $this->createMock(QuestionHelper::class);

        $helper->method('ask')
               ->willReturn('test');
        
        $this->assertEquals([
            'type' => 'array',
            'api' => [
                'name' => 'test'
            ]
        ], StringType::generateTypeWithConsole($output, $input, $helper));
    }

    public function testGetName() : void 
    {
        $this->assertEquals('array', ArrayType::getName());
    }

    public function testGetType() : void 
    {
        $this->assertEquals('array', ArrayType::getType());
    }
}
