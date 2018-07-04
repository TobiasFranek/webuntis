<?php
declare(strict_types=1);

/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */
namespace Webuntis\Tests;

use Webuntis\Repositories\Repository;
use JsonRPC\Client;
use JsonRPC\HttpClient;
use Webuntis\Security\Interfaces\SecurityManagerInterface;

/**
 * Class TestSecurityManager
 * @package Webuntis\Tests
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class TestSecurityManager implements SecurityManagerInterface {

    /**
     * @var int
     */
    private $currentUserId;

    /**
     * @var int
     */
    private $currentUserType;

    /**
     * @var array
     */
    private static $clients; 

    /**
     * @var string
     */
    private $path;

    /** 
     * @var array
     */
    private $config;

    /**
     * @var string
     */
    private $context;
    
    /**
     * @var object
     */
    private $cache;

    /**
     * @var string
     */
    private $session;

    /**
     * TestSecurityManager constructor.
     * @param string $path
     * @param array $config
     * @param string $context
     */
    public function __construct(string $path, array $config, string $context) 
    {
        $this->config = $config;
        $this->path = $path;
        $this->context = $context;

        $this->cache = Repository::getCache();
    }



    /**
     * returns a client of an specific context
     * @return object
     */
    public function getClient() : object 
    {
        return new class() {

            private $data = [
                'getKlassen' => [
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
                ],
                'getSchoolyears' => [
                    [
                        'id' => 2,
                        'name' => '2013/2014',
                        'startDate' => '20130909',
                        'endDate' => '20140706'
                    ],
                    [
                        'id' => 3,
                        'name' => '2014/2015',
                        'startDate' => '20140909',
                        'endDate' => '20150706'
                    ],
                    [
                        'id' => 4,
                        'name' => '2015/2016',
                        'startDate' => '20150909',
                        'endDate' => '20160706'
                    ],
                    [
                        'id' => 5,
                        'name' => '2016/2017',
                        'startDate' => '20160909',
                        'endDate' => '20170706'
                    ]
                ],
                'getCurrentSchoolyear' => [
                    'id' => 2,
                    'name' => '2013/2014',
                    'startDate' => '20130909',
                    'endDate' => '20140706'
                ],
                'getTimetable' => [
                    [
                        'id' => 1,
                        'date' => '20180703',
                        'startTime' => '800',
                        'endTime' => '850',
                        'kl' => [
                            [
                                'id' => 1
                            ]
                        ],
                        'te' => [
                            [
                                'id' => 1
                            ]
                        ],
                        'su' => [
                            [
                                'id' => 1
                            ]
                        ],
                        'ro' => [
                            [
                                'id' => 1
                            ]
                        ]
                    ],
                    [
                        'id' => 2,
                        'date' => '20180703',
                        'startTime' => '900',
                        'endTime' => '950',
                        'kl' => [
                            [
                                'id' => 2
                            ]
                        ],
                        'te' => [
                            [
                                'id' => 2
                            ]
                        ],
                        'su' => [
                            [
                                'id' => 2
                            ]
                        ],
                        'ro' => [
                            [
                                'id' => 2
                            ]
                        ]
                    ],
                    [
                        'id' => 3,
                        'date' => '20180703',
                        'startTime' => '1000',
                        'endTime' => '1050',
                        'kl' => [
                            [
                                'id' => 3
                            ]
                        ],
                        'te' => [
                            [
                                'id' => 3
                            ]
                        ],
                        'su' => [
                            [
                                'id' => 3
                            ]
                        ],
                        'ro' => [
                            [
                                'id' => 3
                            ]
                        ]
                    ]              
                ],
                'getTeachers' => [
                    [
                        'id' => 1,
                        'name' => 'asdman',
                        'foreName' => 'asd',
                        'longName' => 'man'
                    ],
                    [
                        'id' => 2,
                        'name' => 'sapitra',
                        'foreName' => 'sapi',
                        'longName' => 'tra'
                    ],
                    [
                        'id' => 3,
                        'name' => 'manamana',
                        'foreName' => 'mana',
                        'longName' => 'mana'
                    ]
                ],
                'getSubjects' => [
                    [
                        'id' => 1,
                        'name' => 'en',
                        'longName' => 'english'
                    ],
                    [
                        'id' => 2,
                        'name' => 'ger',
                        'longName' => 'german'
                    ],
                    [
                        'id' => 3,
                        'name' => 'fr',
                        'longName' => 'french'
                    ],
                ],
                'getRooms' => [
                    [
                        'id' => 1,
                        'name' => '210',
                        'longName' => 'Second Floor'
                    ],
                    [
                        'id' => 2,
                        'name' => '310',
                        'longName' => 'Third Floor'
                    ],
                    [
                        'id' => 3,
                        'name' => '410',
                        'longName' => 'Fourth Floor'
                    ],
                ],
                'getStudents' => [
                    [
                        'id' => 1,
                        'key' => 1234567,
                        'name' => 'MüllerAle',
                        'foreName' => 'Alexander',
                        'longName' => 'Müller',
                        'gender' => 'male'
                    ],
                    [
                        'id' => 1,
                        'key' => 1234567,
                        'name' => 'SchmidAme',
                        'foreName' => 'Amelie',
                        'longName' => 'Schidt',
                        'gender' => 'female'
                    ]
                ],
                'getExams' => [
                    [
                        'id' => 1,
                        'classes' => [
                            1
                        ],
                        'teachers' => [
                            1
                        ],
                        'students' => [
                            1, 2 
                        ],
                        'subject' => 1,
                        'date' => '20180704',
                        'startTime' => '1400',
                        'endTime' => '1450'
                    ],
                    [
                        'id' => 2,
                        'classes' => [
                            1
                        ],
                        'teachers' => [
                            1
                        ],
                        'students' => [
                            1
                        ],
                        'subject' => 1,
                        'date' => '20180704',
                        'startTime' => '1500',
                        'endTime' => '1550'
                    ]
                ],
                'getExamTypes' => [
                    [
                        'id' => 1,
                        'name' => 'Schularbeit',
                        'longName' => 'Schwere Schularbeit',
                        'type' => 2
                    ]
                ],
                'getSubstitutions' => [
                    [
                        'id' => 1,
                        'type' => 'cancel',
                        'lsid' => 1,
                        'startTime' => '800',
                        'endTime' => '850',
                        'date' => '20180704',
                        'kl' => [
                            [
                                'id' => 1
                            ]
                        ],
                        'te' => [
                            [
                                'id' => 1
                            ]
                        ],
                        'su' => [
                            [
                                'id' => 1
                            ]
                        ],
                        'rooms' => [
                            [
                                'id' => 1
                            ]
                        ],
                        'txt' => 'another teacher'
                    ],
                    [
                        'id' => 2,
                        'type' => 'add',
                        'lsid' => 1,
                        'startTime' => '900',
                        'endTime' => '950',
                        'date' => '20180704',
                        'kl' => [
                            [
                                'id' => 1
                            ]
                        ],
                        'te' => [
                            [
                                'id' => 1
                            ]
                        ],
                        'su' => [
                            [
                                'id' => 1
                            ]
                        ],
                        'rooms' => [
                            [
                                'id' => 1
                            ]
                        ],
                        'txt' => 'sick teacher'
                    ]
                ]
            ];

            public function execute($method) {
                return $this->data[$method];
            }

            public function getData() {
                return $this->data;
            }
        };
    }

    /**
     * returns the id of the current user
     * @return int
     */
    public function getCurrentUserId() : int 
    {
        return 1;
    }

    /**
     * return the type of the current user
     * @return int
     */
    public function getCurrentUserType() : int 
    {
        return 2;
    }

}