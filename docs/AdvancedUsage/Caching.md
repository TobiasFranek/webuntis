# Caching 

You can create an cache instance like this:

```php
$cacheBuilder = new \Webuntis\CacheBuilder\CacheBuilder();

$cache = $cacheBuilder->create();
```

with this caching instance you can do everything the [doctrine MemcachedCache](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/caching.html) class can.

If you want to have another Caching method you can simply add it by yourself.

* create an caching class (like the MemcachedCache from doctrine with fetch, save etc.)
* create a CacheBuilderRoutine where you define how the Caching Instance is created like Webuntis\CacheBuilder\Routines\MemcacheRoutine
* add the routine to the general config off your application

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
        'type' => 'yourtype',
        'host' => 'yourhost',
        'port' => 'yourport,
        'routines' => [
            'yourtype' => 'Your\Namespace'
        ]
    ]
```

in this routine you get the cache config part passed. That means you have full control of the cache config f.e. you could delete host and port and add username and password.

there are 2 console command that affect the cache:

```shell
php vendor/tfranek/webuntis/bin/console.php webuntis:cache:clear [optional port for the memcached server] [optional host for the memcached server]
```

follow the instructions and you can easy clear the cache.

```shell
php vendor/tfranek/webuntis/bin/console.php webuntis:cache:build [<server>] [<school>] [<adminusername>] [<adminpassword>] [<defaultusername>] [<defaultpassword>] [<memcachedhost>] [<memcachedport>] these are optional
```

with this command you can build the cache so that the user doesn't have to do it.
follow the instructions and you can easy build the cache.

with the option --exclude you can exclude multiple Models like this:

```shell
php vendor/tfranek/webuntis/bin/console.php webuntis:cache:build --exclude=Students --exclude=Teachers
```