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
