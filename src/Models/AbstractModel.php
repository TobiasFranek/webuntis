<?php
declare(strict_types=1);

namespace Webuntis\Models;

use Webuntis\Configuration\YAMLConfiguration;
use Webuntis\Models\Interfaces\ModelInterface;
use Webuntis\Serializer\Serializer;
use Webuntis\Types\StringType;
use Webuntis\Types\TypeHandler;
use Ramsey\Uuid\Uuid;

/**
 * The AbstractModel is responsible for the data injection into the different Models.
 * Every Model has to extends this!
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
abstract class AbstractModel implements ModelInterface {
    /**
     * @var string|int
     */
    private $id;

    /**
     * @var string
     */
    const METHOD = '';

    /**
     * Model constructor.
     * @param array $data
     */
    public function __construct(?array $data = []) {
        if(!empty($data)) {
            $this->parse($data);
        }
    }

    /**
     * returns the id
     * @return string|int
     */
    public function getId() 
    {
        return $this->id;
    }

    /**
     * set the id
     * @param int|string $id
     * @return AbstractModel $this
     */
    public function setId($id) : self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * serializes the object and returns an array with the objects values
     * @param $format
     * @return array|string
     */
    public function serialize(?string $format = null) {
        return Serializer::serialize($this, $format);
    }

    /**
     * parses the given data from the json rpc api to the right format for the object
     * @param array $data
     */
    protected function parse(array $data) : void 
    {
        if(isset($data['id'])) {
            $this->setId($data['id']);
        }else {
            $uuid = Uuid::uuid4();
            $this->setId($uuid->toString());
        }
        $fields = YAMLConfiguration::getFields(get_class($this));
        $typeHandler = new TypeHandler();

        $typeHandler->handle($this, $data, $fields);
    }
}