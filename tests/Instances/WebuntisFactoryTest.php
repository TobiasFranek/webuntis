<?php
declare(strict_types=1);

namespace Webuntis\Tests\Instances;

use PHPUnit\Framework\TestCase;
use Webuntis\WebuntisFactory;
use Webuntis\Configuration\WebuntisConfiguration;
use Webuntis\Models\Teachers;
use Webuntis\Models\Classes;

/**
 * WebuntisFactoryTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class WebuntisFactoryTest extends TestCase
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

    public function testFactory() : void
    {   
        $config = [ 
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
        ];

        WebuntisFactory::setConfig($config);

        $this->assertEquals($config, WebuntisFactory::getConfig());

        WebuntisFactory::addConfig('test', [
            'server' => 'yourserver',
            'school' => 'yourschool',
            'username' => 'yourusername',
            'password' => 'yourpassword'
        ]);

        $config['test'] = [
            'server' => 'yourserver',
            'school' => 'yourschool',
            'username' => 'yourusername',
            'password' => 'yourpassword'
        ];

        $this->assertEquals($config, WebuntisFactory::getConfig());

        $default = WebuntisFactory::create(Classes::class);
        $admin = WebuntisFactory::create(Teachers::class);

        $this->assertEquals('default', $default->getContext());

        $this->assertEquals('admin', $admin->getContext());

        $config = [ 
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
            'security_manager' => 'Webuntis\Tests\Util\TestSecurityManager',
            'only_admin' => true
        ];
        WebuntisFactory::setConfig($config);

        $default = WebuntisFactory::create(Classes::class);

        $this->assertEquals('admin', $default->getContext());
    }
}
