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
$result = ExecutionHandler::execute($this, []);
```

this method returns the result of the api request as an array of the parsed objects.

the params are:

* the current repository
* The last Parameter are the Parameters that get passed to the api method

## Adding your custom Repository to the existing ones

you just have to add it into the yml config of the model you want that repo for

## Caching 

If you want to cache data in your repository. To get the Memcached Service call:

```php
self::getCache();
```

this will return an doctrine MemcachedCache object. How to use this MemcachedCache object is documented [here](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/caching.html).
