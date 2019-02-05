<?php
declare(strict_types=1);

namespace Webuntis\Types;


use Webuntis\Configuration\YAMLConfiguration;
use Webuntis\Configuration\WebuntisConfiguration;
use Webuntis\Exceptions\TypeException;
use Webuntis\Models\AbstractModel;
use Webuntis\Types\Interfaces\TypeInterface;

/**
 * manages the different default Types and also loads the custom ones.
 * also executes the right Type by the given data field.
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class TypeHandler {

    /**
     * @var TypeInterface[]
     */
    private static $types = [
        'string' => StringType::class,
        'int' => IntType::class,
        'modelCollection' => ModelCollectionType::class,
        'mergeTimeAndDate' => MergeTimeAndDateType::class,
        'model' => ModelType::class,
        'date' => DateType::class,
        'array' => ArrayType::class
    ];

    public function __construct() {
        $this->loadCustomTypes();
    }

    /**
     * loads the additonal custom types
     */
    private function loadCustomTypes() : void 
    {
        $additionalTypes = YAMLConfiguration::getAdditionalTypes();
        foreach ($additionalTypes as $key => $value) {
            self::$types[$key] = $value;
        }
    }

    /**
     * returns all available Types
     * @return Interfaces\TypeInterface[]
     */
    public static function getAllTypes() : array 
    {
        $additionalTypes = YAMLConfiguration::getAdditionalTypes();
        foreach ($additionalTypes as $key => $value) {
            self::$types[$key] = $value;
        }
        return self::$types;
    }

    /**
     * handles the parse request from the AbstractModel
     * @param AbstractModel $model
     * @param array $data
     * @param array $fields
     * @throws TypeException
     */
    public function handle(AbstractModel &$model, array $data, array $fields) : void 
    {
        $instanceConfig = WebuntisConfiguration::getConfigByModel($model);
        foreach ($fields as $key => $value) {
            $implements = class_implements(self::$types[$value['type']]);
            if (isset($implements[TypeInterface::class])) {
                if(($value['type'] == 'model' || $value['type'] == 'modelCollection') && (isset($instanceConfig['ignore_children']) && $instanceConfig['ignore_children'])) {
                    if($value['type'] == 'model') {
                        if(is_numeric($data[$value['api']['name']]) && $data[$value['api']['name']] < PHP_INT_MAX) {
                            self::$types['int']::execute($model, $data, [$key => $value]);
                        } else {
                            self::$types['string']::execute($model, $data, [$key => $value]);
                        }
                    } else {
                        self::$types['array']::execute($model, $data, [$key => $value]);
                    }
                } else {
                    self::$types[$value['type']]::execute($model, $data, [$key => $value]);
                }
            } else {
                throw new TypeException('this type is not supported');
            }
        }
    }
}