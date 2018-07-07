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

If you have an custom Caching Method or SecurityManager the you can add either --routine to add the class for the caching routine or --securityManagger to add the class for the SecurityManager. When you have written the getConfigMeta right the config will be handled according to this and you can pass the parameters into your command how you defined the configMeta.

# Adding an custom Type

By adding an custom type you can influence how the model handles certain values.

First you have to create an type class that implements the TypeInterface, like this:

```php
class YourType implements TypeInterface {

    /**
     * executes an certain parsing part
     * @param AbstractModel $model
     * @param $data
     * @param $field
     */
    public static function execute(AbstractModel &$model, $data, $field) {
        //todo code
    }

    /**
     * asks for the params according to the type and return an array with the field information
     * @param OutputInterface $output
     * @param InputInterface $input
     * @param $helper
     * @return array
     */
    public static function generateTypeWithConsole(OutputInterface $output, InputInterface $input, $helper) {
        //todo code
    }

    /**
     * return name of the type
     * @return string
     */
    public static function getName() {
        //todo code
    }

    /**
     * return type of the Type Class
     * @return string
     */
    public static function getType() {
        //todo code
    }
}
```

The execute() method takes a model, data and field properties. With that information the method() sets an model value with the right value from the data array, these relations between the data array and the model are defined in the yml configuration.

The generateTypeWithConsole() returns an array that resembles your yml configuration for that type, it will be used to autogenerate Models.

getName() and getType() returns the type f.e \DateTime::class and the name f.e. 'date'.

To add the type to the existing one you have to add it to the certain yml configuration of the model like this:

```yml
Webuntis\Models\Classes:
    repositoryClass: null
    fields:
        name:
            type: string
            api:
                name: name
        fullName:
            type: string
            api:
                name: longName
    additionalTypes:
        yourtypename: Your\Namespace\To\The\Class
```

after this you can start using that custom Type

# Adding an custom Instance

By adding a custom Instance you are able to execute API requests with different users or different servers than the default and the admin one.

First you have to know what is an instance.
For every Configuration f.e. default or admin there will be a new Webuntis object created which is the instance and if you need admin rights for your command the ExecutionHandler executes the command with the admin instance. 

So this is important if you want f.e. an dev instance or you want an instance that is from another server.

## Creating a new ConfigurationModelInterface

First you want to create a new ConfigurationModelInterface like this.

```php
interface YourModelInterface extends ConfigurationModelInterface {
  	// the CONFIG_NAME is the name of your instance
    const CONFIG_NAME = 'yourinstance';
}
```

## Creating a custom Model 

Now you have to create a custom Model, if you don't know how to do this, view this [link](CreatingAnCustomModel.md).
If you have your custom Model you new to implement your newly created ConfigurationModelInterface.
```php
class YouCustomModel extends AbstractModel implements YourModelInterface {
    //your code 
}
```

## Adding your Configuration

Now you have to add your configuration like your admin and default instance, how to do this you see at the [beginning](/docs/BasicUsage/Configuration.md).

now everytime you execute a query with your model this instance will be used.