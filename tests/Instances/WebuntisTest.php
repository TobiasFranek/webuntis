<?php
declare(strict_types=1);

namespace Webuntis\Tests\Instances;

use PHPUnit\Framework\TestCase;
use Webuntis\Webuntis;
use Webuntis\Configuration\WebuntisConfiguration;

final class WebuntisTest extends TestCase
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

    public function testInstance() : void
    {   
        $config = [
            //f.e. thalia, cissa etc.
             'server' => 'yourserver',
             'school' => 'yourschool',
             'username' => 'youradminusername',
             'password' => 'youradminpassword',
             'path_scheme' => '{server}.{school}.com'
        ];

        $instance = new Webuntis($config, 'admin');

        $expected = [
            'id' => 1,
            'name' => 'asdman',
            'firstName' => 'asd',
            'lastName' => 'man'
        ];

        $this->assertEquals($expected, $instance->getCurrentUser()->serialize());
        $this->assertEquals(2, $instance->getCurrentUserType());
        $this->assertEquals('yourserver.yourschool.com', $instance->getPath());
        $this->assertEquals('admin', $instance->getContext());
        $this->assertTrue(method_exists($instance->getClient(), 'execute'));
    }
}
