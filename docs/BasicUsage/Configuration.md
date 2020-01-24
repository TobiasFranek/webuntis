# Configuration

This guide shows you have to set the right configuration.
The basic config array looks like this, but with this configuration you only can access data that needs no right to access.

```php
 'default' => [
       //f.e. thalia, cissa etc.
        'server' => 'yourserver',
        'school' => 'yourschool',
        'username' => 'yourusername',
        'password' => 'yourpassword'
        ]
```

The recommended configuration is when you have an default and an admin configuration, so you can fetch all data from the api. The admin account doesn't have to be an admin account it is enough when you give him full reading permission.

```php
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
    ]
```

if you want to change the path scheme, for example if your domain is not webuntis.com you can use this config. The keywords {school} and {server} will be replaced with the server name and school, you can also leave them out if you don't have such a thing. In summary you can build your own domain/url.

```php
 'default' => [
        //f.e. thalia, cissa etc.
        'server' => 'yourserver',
        'school' => 'yourschool',
        'username' => 'yourusername',
        'password' => 'yourpassword',
        //this is the default path scheme
        'path_scheme' => 'https://{server}.webuntis.com/WebUntis/jsonrpc.do?school={school}'
    ],
  'admin' => [
        //f.e. thalia, cissa etc.
        'server' => 'yourserver',
        'school' => 'yourschool',
        'username' => 'youradminusername',
        'password' => 'youradminpassword',
        //this is the default path scheme
        'path_scheme' => 'https://{server}.webuntis.com/WebUntis/jsonrpc.do?school={school}'
    ]
```

if you want to turn off caching (which is not recommended) take this configuration:

```php
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
    'disable_cache' => true
```

if you don't want the default memcached(port=11211, host=localhost) server use:

```php
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
    'cache' => [
        'type' => 'memcached',
        'host' => 'yourhost',
        'port' => 'yourport
    ]
```

there is also a filebased caching server

```php
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
    'cache' => [
        'type' => 'filesystem',
        'path' => 'your/path/to/the/cache'
    ]
```

if you want to execute all models with the admin instance
```php
  'admin' => [
        //f.e. thalia, cissa etc.
        'server' => 'yourserver',
        'school' => 'yourschool',
        'username' => 'youradminusername',
        'password' => 'youradminpassword'
    ],
    'only_admin' => true
```

if you have changed the vendor dir in the composer.json config you can add this parameter
```php
  'admin' => [
        //f.e. thalia, cissa etc.
        'server' => 'yourserver',
        'school' => 'yourschool',
        'username' => 'youradminusername',
        'password' => 'youradminpassword'
    ],
    'vendor_dir' => 'lib'
```

if you want to use another SecurityManager you can use this config. With an custom Security Manager you can return your own Client. You just have to implement the Webuntis\Security\Interfaces\SecurityManagerInterface and create the method you need (getClient, getCurrentUserId, getCurrentUserType) and return the right things according to the method. With the method getClient() you can return your own client which is then used by the ExecutionHandler to execute http request to apis, the client class must have an call method. F.e. you could return your own Client thats accesses an complietly differently api (not webuntis) and use this library in an complettly other way. 
```php
  'admin' => [
        //f.e. thalia, cissa etc.
        'server' => 'yourserver',
        'school' => 'yourschool',
        'username' => 'youradminusername',
        'password' => 'youradminpassword'
    ],
    'security_manager' => 'Your\Namespace\Manager'
```

Often you have to request api endpoint with normal users for example Periods, but there is the Problem that the childs that you have in the Periods Model like Teachers and Students need admin access, the problem that arises from this is that you get your access denied. With the option ignore_children you can disable the rendering of the children, so you don't need to access there end points too. just add the option to the instance you want it to work on.

```php
use Webuntis\Configuration\WebuntisConfiguration;

$config = new WebuntisConfiguration([ 
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
        'password' => 'youradminpassword'
    ]
 ]);
```

At the moment there is a problem with webuntis that all access right have changed, so your account could be found in the Students Repository but has not the UserType 5. There is now an option per instance to define a default User Repository, where the User will be searched for when you login. When you don't define this property and you have a UserType other then 2 or 5 you will get returned the Webuntis\Models\Account class, which has minimal definition (userId, userType).


```php
use Webuntis\Configuration\WebuntisConfiguration;

$config = new WebuntisConfiguration([ 
'default' => [
       //f.e. thalia, cissa etc.
        'server' => 'yourserver',
        'school' => 'yourschool',
        'username' => 'yourusername',
        'password' => 'yourpassword',

        // when calling ->getCurrentUser() the user will be 
        // searched in the Students repository even if the returned UserType is a Teacher or other
        // $query->get('Students')->findBy(['id' => $this->currentUserId])[0];
        'user_type' => 'Students'
    ],
    'admin' => [
       //f.e. thalia, cissa etc.
        'server' => 'yourserver',
        'school' => 'yourschool',
        'username' => 'youradminusername',
        'password' => 'youradminpassword'
    ]
 ]);
```


To apply the configuration you have to simply create a new WebuntisConfiguration object.

```php
use Webuntis\Configuration\WebuntisConfiguration;

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
    ]
 ]);
```
