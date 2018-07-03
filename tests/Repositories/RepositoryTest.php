<?php
declare(strict_types=1);

namespace Webuntis\Tests\Query;

use PHPUnit\Framework\TestCase;
use Webuntis\Repositories\Repository;
use Webuntis\Models\Classes;
use Webuntis\Models\SchoolYears;
use Webuntis\Handler\ExecutionHandler;

final class RepositoryTest extends TestCase
{
    private $classesModels = [];

    private $executionHandler;

    protected function setUp()
    {
        $this->executionHandler = $this->getMockBuilder(ExecutionHandler::class)
                                       ->getMock();
        $dataClasses = [
            [
                'id' => 1,
                'name' => 'test',
                'longName' => 'teststring'
            ],
            [
                'id' => 2,
                'name' => 'sie',
                'longName' => 'nice'
            ],
            [
                'id' => 3,
                'name' => 'valid',
                'longName' => 'one'
            ],
            [
                'id' => 4,
                'name' => 'thisisastring',
                'longName' => 'thisisanevenlongerstring'
            ]
        ];

        $dataYears = [
            [
                'id' => 2,
                'name' => '2013/2014',
                'startDate' => '2013-09-09T00:00:00+0200',
                'endDate' => '2014-07-06T00:00:00+0200'
            ],
            [
                'id' => 3,
                'name' => '2014/2015',
                'startDate' => '2014-09-09T00:00:00+0200',
                'endDate' => '2015-07-06T00:00:00+0200'
            ],
            [
                'id' => 4,
                'name' => '2015/2016',
                'startDate' => '2015-09-09T00:00:00+0200',
                'endDate' => '2016-07-06T00:00:00+0200'
            ],
            [
                'id' => 5,
                'name' => '2016/2017',
                'startDate' => '2016-09-09T00:00:00+0200',
                'endDate' => '2017-07-06T00:00:00+0200'
            ]
            ];

        $this->classesModels = [];

        foreach($dataClasses as $modeldataClasses) {
            $this->classesModels[] = new Classes($modeldataClasses);
        }

        $this->yearsModels = [];

        foreach($dataYears as $modeldataYears) {
            $this->yearsModels[] = new Schoolyears($modeldataYears);
        }

        $this->executionHandler->expects($this->any())
                               ->method('execute')
                               ->will($this->returnCallback([$this, 'executeCallback']));
    }

    public function testFindAll() : void
    {   
        $repository = new Repository(Classes::class, $this->executionHandler);
        $this->assertEquals($this->classesModels, $repository->findAll());

        $this->assertEquals(array_reverse($this->classesModels), $repository->findAll(['id' => 'DESC']));
        $this->assertEquals(2, count($repository->findAll([], 2)));
    }

    public function testFindBy() : void 
    {
        $repository = new Repository(Classes::class, $this->executionHandler);

        $expected = [
            $this->classesModels[2]
        ];
        $this->assertEquals($expected, $repository->findBy(['name' => '%d%']));
        $this->assertEquals($expected, $repository->findBy(['name' => 'valid']));

        $repository = new Repository(Schoolyears::class, $this->executionHandler);

        $expected = [
            $this->yearsModels[0],
            $this->yearsModels[1],
            $this->yearsModels[2]
        ];

        $this->assertEquals($expected, $repository->findBy(['startDate' => '>2013-01-01', 'endDate' => '<2016-12-31']));
    }

    public function executeCallback($repo) {
        if($repo->getModel()::METHOD == 'getKlassen') {
            return $this->classesModels;
        } else if($repo->getModel()::METHOD == 'getSchoolyears') {
            return $this->yearsModels;
        }
    }
}
