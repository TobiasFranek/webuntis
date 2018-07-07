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