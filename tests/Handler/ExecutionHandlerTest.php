<?php
declare(strict_types=1);

namespace Webuntis\Tests\Handler;

use PHPUnit\Framework\TestCase;
use Webuntis\Repositories\Repository;
use Webuntis\Models\Classes;
use Webuntis\Configuration\WebuntisConfiguration;
use Webuntis\Handler\ExecutionHandler;

/**
 * ExecutionHandlerTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class ExecutionHandlerTest extends TestCase
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

    public function testCreate() : void
    {   
        $executionHandler = new ExecutionHandler();

        $repo = new Repository(Classes::class);

        $data = $executionHandler->execute($repo, []);

        $expected = [
            [
                'id' => 1,
                'name' => 'test',
                'fullName' => 'teststring'
            ],
            [
                'id' => 2,
                'name' => 'sie',
                'fullName' => 'nice'
            ],
            [
                'id' => 3,
                'name' => 'valid',
                'fullName' => 'one'
            ],
            [
                'id' => 4,
                'name' => 'thisisastring',
                'fullName' => 'thisisanevenlongerstring'
            ]
        ];

        $this->assertEquals($expected, \Webuntis\Serializer\Serializer::serialize($data));
    }
}
