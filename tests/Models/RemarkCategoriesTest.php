<?php
declare(strict_types=1);

namespace Webuntis\Tests\Models;

use PHPUnit\Framework\TestCase;
use Webuntis\Models\RemarkCategories;
use Webuntis\Configuration\WebuntisConfiguration;

/**
 * RemarkCategoriesTest
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
final class RemarkCategoriesTest extends TestCase
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
            'name' => 'test',
            'longName' => 'teststring',
            'groupId' => 1
        ];

        $remarkCategories = new RemarkCategories($data);

        $this->assertEquals($remarkCategories->getAttributes(), $data);

        $this->assertEquals(1, $remarkCategories->getId());
        $this->assertEquals(1, $remarkCategories->getGroup()->getId());
        $this->assertEquals('test', $remarkCategories->getName());
        $this->assertEquals('teststring', $remarkCategories->getFullName());

        $expected = [
            'id' => 1,
            'name' => 'test',
            'fullName' => 'teststring',
            'group' => [
                'id' => 1,
                'name' => 'Group1'
            ]
        ];

        $this->assertEquals($expected, $remarkCategories->serialize());
        $this->assertEquals(json_encode($expected), $remarkCategories->serialize('json'));

        new WebuntisConfiguration([ 
            'default' => [
                   //f.e. thalia, cissa etc.
                    'server' => 'yourserver',
                    'school' => 'yourschool',
                    'username' => 'yourusername',
                    'password' => 'yourpassword',
                    'ignore_children' => true
                ],
            'admin' => [
                   //f.e. thalia, cissa etc.
                    'server' => 'yourserver',
                    'school' => 'yourschool',
                    'username' => 'youradminusername',
                    'password' => 'youradminpassword',
                    'ignore_children' => true
            ],
            'security_manager' => 'Webuntis\Tests\Util\TestSecurityManager'
        ]);

        $remarkCategories = new RemarkCategories($data);

        $expected = [
            'id' => 1,
            'name' => 'test',
            'fullName' => 'teststring',
            'group' => 1
        ];
        
        $this->assertEquals($expected, $remarkCategories->serialize());
    }
}
