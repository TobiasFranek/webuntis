# Creating an custom Repository

Creating a custom Repository, so you can have custom query methods is pretty simple.

first you have to make a Repository Class like this:

```shell
php vendor/tfranek/webuntis/bin/console.php webuntis:generate:repository [optional path to the model]
```
you can still use the default methods like findBy() and findAll().

there are also function like parse() and find() to make the creation of an custom Repository easier.

* find() the find method searches the given parsed objects for the given parameters

Usage:

```php
$searchedForObjects = $this->find($parsedObjects, $params);
```

* sort() the sort method sorts the given data

Usage:

```php
$searchedForObjects = $this->sort($parsedObjects, $field, $sortingOrder);
```
* parse()the parse method parses an result automatically to the right model

Note: The ExecutionHandler also parses the result, so you dont really have to do it

Usage:

```php
$parsedObjects = $this->parse($result);
```

I highly recommend these methods!

## Executing your own API Request

to execute your own api request in you custom Repository is pretty easy.

just write this:

```php
$result = $this->executionHandler->execute($this, []);
```

this method returns the result of the api request as an array of the parsed objects.

the params are:

* the current repository
* The last Parameter are the Parameters that get passed to the api method

## Adding your custom Repository to the existing ones

you just have to add a line into the yml config of the model you want that repo for.

with that line:

repositoryClass: Your\Path\Repository

## Caching 

See [Caching](Caching.md)

# Creating an custom Model

Why would you create a custom Model? It is simple, what if you want different fields or you want to add a Model that doesn't exist yet.

First you have to learn how a Model is assembled every model inherits from the AbstractModel. But it is important to know that you implement the right Interfaces because the Interfaces say if the Model is cacheable or needs admin rights to execute an certain command.

If you have installed the package over composer you can use this command:

```shell
php vendor/tfranek/webuntis/bin/console.php webuntis:generate:model
```

Follow the instruction in the console.

## Types

For the models there are different types and these are defined as types, types that are integrated in the core are:

* int - resembles the int type
* string - is a string
* date - is a \DateTime that consist of on date
* mergeTimeAndDate - is a type that merges time and date to one \DateTime
* model - is an subordinate object that need parameter to find it in the api
* modelCollection - is an collection of subordinate objects

## YML Configuration

All your configuration that you set(fieldnames, custom repos) are saved in .yml files with these files the class can automatically generate a parse() function to assign the right api value to the right model values.


# Caching 

You can create an cache instance like this:

```php
$cacheBuilder = new \Webuntis\CacheBuilder\CacheBuilder();

$cache = $cacheBuilder->create();
```

with this caching instance you can do everything the [doctrine MemcachedCache](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/caching.html) class can.

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

