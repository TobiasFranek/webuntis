<?php
declare(strict_types=1);

namespace Webuntis\Models;

use Webuntis\Models\AbstractModel;
use Webuntis\Models\Interfaces\CachableModelInterface;
use Webuntis\Models\Interfaces\AdministrativeModelInterface;
use DateTime;
use Webuntis\Exceptions\ModelException;
use Webuntis\Models\Interfaces\ModelInterface;

/**
 * ClassRegEvents Model
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class ClassRegEvents extends AbstractModel implements CachableModelInterface, AdministrativeModelInterface
{
    const CACHE_LIFE_TIME = 86400;
    const METHOD = "getClassregEvents";

    /**
     * @var AbstractModel
     */
    private $student;

    /**
     * @var DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $reason;

    /**
     * @var string
     */
    private $text;

    /**
     * @var ModelInterface
     */
    private $category;

    /**
     * sets category
     * @param ModelInterface
     * @return ModelInterface
     */
    public function setCategory(ModelInterface $category) : ModelInterface 
    {
        $this->category = $category;

        return $this;
    }

    /**
     * return category
     * @return ModelInterface
     */
    public function getCategory() : ModelInterface
    {
        return $this->category;
    }

    /**
     * Getter for student
     *
     * @return AbstractModel
     */
    public function getStudent() : AbstractModel
    {
        return $this->student;
    }

    /**
     * Setter for student
     *
     * @param AbstractModel $student
     * @return ClassRegEvents
     */
    public function setStudent(AbstractModel $student) : self
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Getter for date
     *
     * @return DateTime
     */
    public function getDate() : DateTime
    {
        return $this->date;
    }

    /**
     * Setter for date
     *
     * @param DateTime $date
     * @return ClassRegEvents
     */
    public function setDate(DateTime $date) : self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Getter for subject
     *
     * @return string
     */
    public function getSubject() : string
    {
        return $this->subject;
    }

    /**
     * Setter for subject
     *
     * @param string $subject
     * @return ClassRegEvents
     */
    public function setSubject(string $subject) : self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Getter for reason
     *
     * @return string
     */
    public function getReason() : string
    {
        return $this->reason;
    }

    /**
     * Setter for reason
     *
     * @param string $reason
     * @return ClassRegEvents
     */
    public function setReason(string $reason) : self
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Getter for text
     *
     * @return string
     */
    public function getText() : string
    {
        return $this->text;
    }

    /**
     * Setter for text
     *
     * @param string $text
     * @return ClassRegEvents
     */
    public function setText(string $text) : self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * sets an given field
     *
     * @param mixed $field
     * @param mixed $value
     * @return ClassRegEvents
     */
    public function set($field, $value) : self
    {
        $this->$field = $value;

        return $this;
    }

    /**
     * return the children by given key
     *
     * @param string $key
     *
     * @return AbstractModel[]
     * @throws ModelException
     */
    public function get($key) : array
    {
        switch ($key) {
            case 'student':
                return [$this->student];
            default:
                throw new ModelException("array of objects $key doesn't exist");
        }
    }
}
