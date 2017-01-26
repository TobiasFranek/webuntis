# Creating an custom Repository

Creating a custom Repository, so you can have custom query methods is pretty simple.

first you have to make a Repository Class which inherits the default Repository Class, like this:

```php
class YourRepository extends Repository {
	//you custom repository code and methods
}
```
you still can use the default methods like findBy() and findAll().

there are also function like parse() and find() to make the creation of an custom Repository easier.

* parse()the parse method parses an result automatically to the right model

Usage:

```php
$parsedObjects = $this->parse($result);
```

* find() the find method searches the given parsed objects for the given parameters

Usage:

```php
$searchedForObjects = $this->find($parsedObjects, $params);
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

# Creating an custom Model

Why would you create a custom Model? It is simple, what if you want different fields or you want to add a Model that doesn't exist yet.

First you have to learn how a Model is assembled every model inherits from the AbstractModel and there are mandatory methods like parse() and serialize(). But it is important to know that you implement the right Interfaces because the Interfaces say if the Model is Cacheable or need Adminrights to execute the command.

you need to define your model like this:

```php
class YourModel extends AbstractModel {

    //this could be somthing like this ('firstName', 'lastName')
    private $modelField;

    //this could be somthing like this ('firstName', 'lastName')
    private $anotherModelField;

    //defines the method that has to be executed to get the data from the API
    const METHOD = 'apiMethod';

    protected function parse($data) {
        //how to parse the given data to your model
        $this->modelField = $data['apiField'];
        $this->anotherModelField = $data['anotherApiField'];
    }

 	//please write getter and setter for the Model fields, or i will be sad :(
}
```

which Interface you implement is important f.e. if you implement

```php
class YourModel extends AbstractModel implements CachableModelInterface {
    //your code
}
```

Your custom Model will be cached.

if you implement

```php
class YourModel extends AbstractModel implements AdministrativeModelInterface {
    //your code
}
```

Then your Model can only be executed with admin rights so the ExecutionHandler executes the API Request with your admin configuration that you added at the [beginning](/docs/BasicUsage/Configuration.md).

## Adding your custom Model to the existing ones

you just have to add you custom Model to the Query constructor like this:

```php
$query = new Query([
    'ModelName' => \Your\Model::class
])
```

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
