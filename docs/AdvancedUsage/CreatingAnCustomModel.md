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
    
    //if you have models like teachers or something in your model you might wanna 
    //search for properties of the model, for this you will need this get() method
    //because it is used by the recursive search
    public function get($key) {
        //do something
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
