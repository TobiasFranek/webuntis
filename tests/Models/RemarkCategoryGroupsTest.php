<?php
declare(strict_types=1);

namespace Webuntis\Tests\Models;

use PHPUnit\Framework\TestCase;
use Webuntis\Models\RemarkCategoryGroups;
use Webuntis\Configuration\WebuntisConfiguration;

/**
 * RemarkCategoryGroupsTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class RemarkCategoryGroupsTest extends TestCase
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
        $data = [
            'id' => 1,
            'name' => 'test'
        ];

        $remarkCategoryGroups = new RemarkCategoryGroups($data);

        $this->assertEquals($remarkCategoryGroups->getAttributes(), $data);

        $this->assertEquals(1, $remarkCategoryGroups->getId());
        $this->assertEquals('test', $remarkCategoryGroups->getName());

        $this->assertEquals($data, $remarkCategoryGroups->serialize());
        $this->assertEquals(json_encode($data), $remarkCategoryGroups->serialize('json'));
    }
}
