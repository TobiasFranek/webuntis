<?php
declare(strict_types=1);

namespace Webuntis\Tests\Types;

use PHPUnit\Framework\TestCase;
use Webuntis\Repositories\UserRepository;
use Webuntis\Models\Teachers;
use Webuntis\Models\AbstractModel;
use Webuntis\Types\ModelType;
use Webuntis\Handler\ExecutionHandler;
use Webuntis\Configuration\WebuntisConfiguration;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Helper\QuestionHelper;

/**
 * ModelTypeTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class ModelTypeTest extends TestCase
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
            'modelTest' => [
                'type' => 'model',
                'api' => [
                    'name' => 'testModel'
                ],
                'model' => [
                    'name' => 'Teachers',
                    'searchkey' => 'id'
                ]
            ]
        ];
        $data = [
            'testModel' => '1'
        ];

        $test = new class extends AbstractModel {
            private $modelTest;

            public function set(string $field, $value) 
            {
                $this->$field = $value;
            }

            public function getModelTest() 
            {
                return $this->modelTest;
            }
        };

        ModelType::execute($test, $data, $field);

        $this->assertInstanceOf(Teachers::class, $test->getModelTest());
        $this->assertEquals(1, $test->getModelTest()->getId());
    }

    public function testGenerateTypeWithConsole() : void
    {
        $output = $this->createMock(Output::class);
        $input = $this->createMock(Input::class);
        $helper = $this->createMock(QuestionHelper::class);

        $helper->method('ask')
               ->willReturn('test');
        
        $this->assertEquals([
            'type' => 'model',
            'api' => [
                'name' => 'test'
            ],
            'model' => [
                'name' => 'test',
                'searchkey' => 'test'
            ]
        ], ModelType::generateTypeWithConsole($output, $input, $helper));
    }

    public function testGetName() : void 
    {
        $this->assertEquals('model', ModelType::getName());
    }

    public function testGetType() : void 
    {
        $this->assertEquals(AbstractModel::class, ModelType::getType());
    }
}
