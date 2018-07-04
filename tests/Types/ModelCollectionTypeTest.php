<?php
declare(strict_types=1);

namespace Webuntis\Tests\Types;

use PHPUnit\Framework\TestCase;
use Webuntis\Repositories\UserRepository;
use Webuntis\Models\Teachers;
use Webuntis\Models\AbstractModel;
use Webuntis\Types\ModelCollectionType;
use Webuntis\Handler\ExecutionHandler;
use Webuntis\Configuration\WebuntisConfiguration;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Helper\QuestionHelper;


final class ModelCollectionTypeTest extends TestCase
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
            'security_manager' => 'Webuntis\Tests\TestSecurityManager'
        ]);
    }

    public function testExecute() : void
    {   
        $field = [
            'modelCollectionTest' => [
                'type' => 'modelCollection',
                'api' => [
                    'name' => 'teachers',
                    'searchkey' => 'id'
                ],
                'model' => [
                    'name' => 'Teachers',
                    'searchkey' => 'id'
                ]
            ]
        ];
        $data = [
            'teachers' => [
                [
                    'id' => 1
                ],
                [
                    'id' => 2
                ]
            ]
        ];

        $test = new class extends AbstractModel {
            private $modelCollectionTest;

            public function set(string $field, $value) 
            {
                $this->$field = $value;
            }

            public function getModelCollectionTest() 
            {
                return $this->modelCollectionTest;
            }
        };

        ModelCollectionType::execute($test, $data, $field);

        $this->assertEquals(2, count($test->getModelCollectionTest()));
        $this->assertInstanceOf(Teachers::class, $test->getModelCollectionTest()[0]);
        $this->assertInstanceOf(Teachers::class, $test->getModelCollectionTest()[1]);
    }

    public function testGenerateTypeWithConsole() : void
    {
        $output = $this->createMock(Output::class);
        $input = $this->createMock(Input::class);
        $helper = $this->createMock(QuestionHelper::class);

        $helper->method('ask')
               ->willReturn('test');
        
        $this->assertEquals([
            'type' => 'modelCollection',
            'api' => [
                'name' => 'test',
                'searchkey' => 'test'
            ],
            'model' => [
                'name' => 'test',
                'searchkey' => 'test'
            ]
        ], ModelCollectionType::generateTypeWithConsole($output, $input, $helper));
    }

    public function testGetName() : void 
    {
        $this->assertEquals('modelCollection', ModelCollectionType::getName());
    }

    public function testGetType() : void 
    {
        $this->assertEquals('\\' . AbstractModel::class . '[]', ModelCollectionType::getType());
    }
}
