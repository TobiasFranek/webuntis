<?php
declare(strict_types=1);

namespace Webuntis\Tests\Types;

use PHPUnit\Framework\TestCase;
use Webuntis\Repositories\UserRepository;
use Webuntis\Models\Holidays;
use Webuntis\Models\AbstractModel;
use Webuntis\Types\MergeTimeAndDateType;
use Webuntis\Handler\ExecutionHandler;
use Webuntis\Configuration\WebuntisConfiguration;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Helper\QuestionHelper;


final class MergeTimeAndDateTypeTest extends TestCase
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
            'timeTest' => [
                'type' => 'mergeTimeAndDate',
                'api' => [
                    'time' => 'testTime',
                    'date' => 'testDate'
                ]
            ]
        ];
        $data = [
            'testTime' => '800',
            'testDate' => '20180704'
        ];

        $test = new class extends AbstractModel {
            private $timeTest;

            public function set(string $field, $value) 
            {
                $this->$field = $value;
            }

            public function getTimeTest() 
            {
                return $this->timeTest;
            }
        };

        MergeTimeAndDateType::execute($test, $data, $field);

        $this->assertEquals(new \DateTime('2018-07-04 08:00'), $test->getTimeTest());
    }

    public function testGenerateTypeWithConsole() : void
    {
        $output = $this->createMock(Output::class);
        $input = $this->createMock(Input::class);
        $helper = $this->createMock(QuestionHelper::class);

        $helper->method('ask')
               ->willReturn('test');
        
        $this->assertEquals([
            'type' => 'mergeTimeAndDate',
            'api' => [
                'time' => 'test',
                'date' => 'test'
            ]
        ], MergeTimeAndDateType::generateTypeWithConsole($output, $input, $helper));
    }

    public function testGetName() : void 
    {
        $this->assertEquals('mergeTimeAndDate', MergeTimeAndDateType::getName());
    }

    public function testGetType() : void 
    {
        $this->assertEquals(\DateTime::class, MergeTimeAndDateType::getType());
    }
}
