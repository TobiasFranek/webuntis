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