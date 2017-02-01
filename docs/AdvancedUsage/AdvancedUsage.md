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

Usage:

```php
$parsedObjects = $this->parse($result);
```

I highly recommend these methods!

## Executing your own API Request

to execute your own api request in you custom Repository is pretty easy.

just write this:

```php
$result = ExecutionHandler::execute($this, []);
```

this method return the result of the api request as an array.

the params are:

* Model ($this->model is already defined if you inherit the default Repository Class)
* Instance ($this->instance is already defined if you inherit the default Repository Class)
* The last Parameter are the Parameters that get passed to the api method

## Adding your custom Repository to the existing ones

you just have to add you custom Repository to the Query constructor like this:

```php
$query = new Query([], [
    'ModelName' => \Your\RepoClass::class
])
```

the 'ModelName' is the name of the Model you want for your Repository f.e. 'Subjects'

now everytime you call:

```php
$query->get('ModelName')->yourMethod();
```

your Repository gets executed.

## Caching 

If you want to cache data in your repository. To get the Memcached Service call:

```php
self::getCache();
```

this will return an doctrine MemcachedCache object. How to use this MemcachedCache object is documented [here](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/caching.html).

# Creating an custom Model

Why would you create a custom Model? It is simple, what if you want different fields or you want to add a Model that doesn't exist yet.

First you have to learn how a Model is assembled every model inherits from the AbstractModel and there are mandatory methods like parse() and serialize(). But it is important to know that you implement the right Interfaces because the Interfaces say if the Model is Cacheable or need Adminrights to execute the command.

if you have installed the package over composer you can use this command

```shell
php vendor/tfranek/webuntis/bin/console.php webuntis:generate:model
```

follow the instruction in the console.

##Types

For the models there are different types and these are defined as types, types that are integrated in the core are:

* int - resembles the int type
* string - is a string
* date - is a \DateTime that consist of on date
* mergeTimeAndDate - is a type that merges time and date to one \DateTime
* model - is an subordinate object that need parameter to find it in the api
* modelCollection - is an collection of subordinate objects

##YML Configuration

All your configuration that you set(fieldnames, custom repos) are saved in .yml files with these files the class can automatically generate a parse() function to assign the right api value to the right model values.

# Adding an custom Instance

By adding a custom Instance you are able to execute API requests with different users or different servers than the default and the admin one.

First you have to know what is an instance.
For every Configuration f.e. default or admin there will be a new Webuntis object created which is the instance and if you need admin right for your command the ExecutionHandler executes the command with the admin instance. 

So this is important if you want f.e. an dev instance or you want an instance that is from another server.

## Creating a new ConfigurationModelInterface

first you want to create a new ConfigurationModelInterface like this.

```php
interface YourModelInterface extends ConfigurationModelInterface {
  	// the CONFIG_NAME is the name of your instance
    const CONFIG_NAME = 'yourinstance';
}
```

## Creating a custom Model 

now you have to create a custom Model how to do this, can you learn [here](CreatingAnCustomModel.md).
if you have your custom Model you new to implement your newly created ConfigurationModelInterface.
```php
class YouCustomModel extends AbstractModel implements YourModelInterface {
    //your code 
}
```

## Adding your Configuration

if you already have a WebuntisConfiguration object you can simply add an instance like this, otherwise you can add it like the admin or default configuration which is shown at the [beginning](/docs/BasicUsage/Configuration.md).

```php
$yourConfigObj->addConfig([
    'yourinstance' => [
        'server' => 'yourserver',
        'school' => 'yourschool',
        'username' => 'yourusername',
        'passsword' => 'yourpassword'
    ]
])
```

now everytime you execute a query with your model this instance will be used.

# Adding an custom Type

By adding an custom type you can influence how the model handles certain values.

First you have to create an type class that implements TypeInterface, like this:

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

you have to add and also code these methods, because there are used to handle the things for the models.

the execute() method takes a model, data and field properties with that information the method() sets an model value with the right value from the data array, these relations between the data array and the model are defined in the yml configuration

the generateTypeWithConsole() return an array that resembles your yml configuration for that type

getName() and getType() return the type f.e \DateTime::class and the name f.e. 'date'

to add the type to the existing one you have to add it to the certain yml configuration of the model like this:

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

# Caching 

There are some things to know about caching the caching instance you cann simply access with:

```php
self::getCache();
```

with this caching instance you can do everything the [doctrine MemcachedCache](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/caching.html) class can 

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
