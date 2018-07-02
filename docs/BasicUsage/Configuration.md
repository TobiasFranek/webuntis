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

if you want to change the path schme, for example if your domain is not webuntis.com you can use this config. The keywords {school} and {server} will be replaced with the server name and school, you can also leave them out if you don't have such a thing. In summary you can build your own domain/url.

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
    'disableCache' => true
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
    'memcached' => [
        'host' => 'yourhost',
        'port' => 'yourport
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
    'vendorDir' => 'lib'
```

if you want to use another SecurityManager you can use this config
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
