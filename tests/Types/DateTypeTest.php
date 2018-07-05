<?php
declare(strict_types=1);

namespace Webuntis\Tests\Types;

use PHPUnit\Framework\TestCase;
use Webuntis\Repositories\UserRepository;
use Webuntis\Models\Holidays;
use Webuntis\Models\AbstractModel;
use Webuntis\Types\DateType;
use Webuntis\Handler\ExecutionHandler;
use Webuntis\Configuration\WebuntisConfiguration;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Helper\QuestionHelper;


final class DateTypeTest extends TestCase
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
            'dateTest' => [
                'type' => 'date',
                'api' => [
                    'name' => 'testDate'
                ]
            ]
        ];
        $data = [
            'testDate' => '20180704'
        ];

        $test = new class extends AbstractModel {
            private $dateTest;

            public function set(string $field, $value) 
            {
                $this->$field = $value;
            }

            public function getDateTest() 
            {
                return $this->dateTest;
            }
        };


        DateType::execute($test, $data, $field);

        $this->assertEquals(new \DateTime('20180704'), $test->getDateTest());
    }

    public function testGenerateTypeWithConsole() : void
    {
        $output = $this->createMock(Output::class);
        $input = $this->createMock(Input::class);
        $helper = $this->createMock(QuestionHelper::class);

        $helper->method('ask')
               ->willReturn('test');
        
        $this->assertEquals([
            'type' => 'date',
            'api' => [
                'name' => 'test'
            ]
        ], DateType::generateTypeWithConsole($output, $input, $helper));
    }

    public function testGetName() : void 
    {
        $this->assertEquals('date', DateType::getName());
    }

    public function testGetType() : void 
    {
        $this->assertEquals(\DateTime::class, DateType::getType());
    }
}
