<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace Webuntis\Types;


use Webuntis\Configuration\YAMLConfiguration;
use Webuntis\Exceptions\TypeException;
use Webuntis\Models\AbstractModel;
use Webuntis\Types\Interfaces\TypeInterface;

/**
 * Class TypeHandler
 * @package Webuntis\Types
 * @author Tobias Franek <tobias.franek@gmail.com>
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
        'date' => DateType::class
    ];

    public function __construct() {
        $this->loadCustomTypes();
    }

    /**
     * loads the additonal custom types
     */
    private function loadCustomTypes() {
        $additionalTypes = YAMLConfiguration::getAdditionalTypes();
        foreach ($additionalTypes as $key => $value) {
            self::$types[$key] = $value;
        }
    }

    /**
     * returns all available Types
     * @return Interfaces\TypeInterface[]
     */
    public static function getAllTypes() {
        $additionalTypes = YAMLConfiguration::getAdditionalTypes();
        foreach ($additionalTypes as $key => $value) {
            self::$types[$key] = $value;
        }
        return self::$types;
    }

    /**
     * handles the parse request from the AbstractModel
     * @param AbstractModel $model
     * @param $data
     * @param $fields
     */
    public function handle(AbstractModel &$model, $data, $fields) {
        foreach($fields as $key => $value) {
            $implements = class_implements(self::$types[$value['type']]);
            if(isset($implements[TypeInterface::class])) {
                self::$types[$value['type']]::execute($model, $data, [$key => $value]);
            }else {
                throw new TypeException('this type is not supported');
            }
        }
    }
}