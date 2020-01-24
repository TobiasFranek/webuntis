<?php
declare(strict_types=1);

namespace Webuntis\Tests\Repositories;

use PHPUnit\Framework\TestCase;
use Webuntis\Configuration\WebuntisConfiguration;
use Webuntis\Security\WebuntisSecurityManager;
use Webuntis\Client\Client;

/**
 * WebuntisSecurityManagerTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class WebuntisSecurityManagerTest extends TestCase
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
        ]);
    }
    public function testSecurityManager() : void
    {   

        $clientMock = $this->createMock(Client::class);

        $clientMock->method('call')
               ->willReturn([
                'sessionId' => '644AFBF2C1B592B68C6B04938BD26965',
                'personType' => '5',
                'personId' => '1'
        ]);

        $manager = new WebuntisSecurityManager(
            'yourserver.yourschool.com', 
            [
                //f.e. thalia, cissa etc.
                'server' => 'yourserver',
                'school' => 'yourschool',
                'username' => 'youradminusername',
                'password' => 'youradminpassword'
            ],
            'admin',
            $clientMock
        );

        $client = $manager->getClient();

        $this->assertEquals([
            'sessionId' => '644AFBF2C1B592B68C6B04938BD26965',
            'personType' => '5',
            'personId' => '1'
        ], $client->call('authenticate', ['youradminusername', 'youradminpassword', rand(1, 4000)]));

        $this->assertEquals(1, $manager->getCurrentUserId());
        $this->assertEquals(5, $manager->getCurrentUserType());
    }

}
