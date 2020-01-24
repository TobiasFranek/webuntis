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