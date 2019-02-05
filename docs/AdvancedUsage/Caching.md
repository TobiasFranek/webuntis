# Caching 

At the moment there are 3 available options for caching:

* memcached - works with an memcached server and the php extension
* filebased caching - stores the data in files (be careful with outdated data)
* array cache - which stores the data in the php run-time, but can not be persistet across processes

You can create an cache instance like this:

```php
$cacheBuilder = new \Webuntis\CacheBuilder\CacheBuilder();

$cache = $cacheBuilder->create();
```

with this caching instance you can do everything the [doctrine Cache](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/caching.html) class can.

If you want to have another Caching method (like f.e. filebased caching) you can simply add it by yourself.

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

In this routine you get the cache config part passed. That means you have full control of the cache config f.e. you could delete host and port and add username and password.

There are 2 console command that affect the cache:

```shell
php vendor/tfranek/webuntis/bin/console.php webuntis:cache:clear [optional port for the memcached server] [optional host for the memcached server]
```

This command is for clearing the memcache.

```shell
php vendor/tfranek/webuntis/bin/console.php webuntis:cache:build [<server>] [<school>] [<adminusername>] [<adminpassword>] [<defaultusername>] [<defaultpassword>] [<memcachedhost>] [<memcachedport>] these are optional
```

With this command you can build the cache so that the user doesn't have to do it.

With the option --exclude you can exclude multiple Models like this:

```shell
php vendor/tfranek/webuntis/bin/console.php webuntis:cache:build --exclude=Students --exclude=Teachers
```

If you have an custom Caching Method or SecurityManager the you can add either --routine to add the class for the caching routine or --securityManagger to add the class for the SecurityManager. When you have written the getConfigMeta right the config will be handled according to this and you can pass the parameters into your command how you defined the configMeta.