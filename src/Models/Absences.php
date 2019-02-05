<?php
declare(strict_types=1);

namespace Webuntis\Models;

use Webuntis\Exceptions\ModelException;
use Webuntis\Query\Query;
use JMS\Serializer\Annotation\SerializedName;
use Webuntis\Models\Interfaces\AdministrativeModelInterface;
use Webuntis\Types\ModelType;
use Webuntis\Configuration\YAMLConfiguration;
use Webuntis\Models\AbstractModel;

/**
 * Absences Model
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class Absences extends AbstractModel implements AdministrativeModelInterface {

    /**
     * @SerializedName("startTime")
     * @var \DateTime
     */
    private $startTime;

    /**
     * @SerializedName("endTime")
     * @var \DateTime
     */
    private $endTime;

    /**
     * @var string|object
     */
    private $student;

    /**
     * @var string|object
     */
    private $subject;

    /**
     * @var array
     */
    private $teachers;

    /**
     * @SerializedName("studentGroup")
     * @var string
     */
    private $studentGroup;

    /**
     * @var string
     */
    private $status;

    /**
     * @SerializedName("absenceReason")
     * @var string
     */
    private $absenceReason;

    /**
     * @SerializedName("absentTime")
     * @var int
     */
    private $absentTime;

     /**
     * @SerializedName("excuseStatus")
     * @var string
     */
    private $excuseStatus;

    /**
     * @var string
     */
    private $user;

    /**
     * @var bool
     */
    private $checked;

    /**
     * @var bool
     */
    private $invalid;

   
    /**
     * @var string
     */
    const METHOD = "getTimetableWithAbsences";


    /**
     * gets student
     * @return mixed
     */
    public function getStudent() 
    {
        return $this->student;
    }

    /**
     * sets student
     * @param mixed
     * @return ModelInterface
     */
    public function setStudent($student) : ModelInterface 
    {
        $this->student = $student;

        return $this;
    }

    /**
     * gets subject
     * @return mixed
     */
    public function getSubject() 
    {
        return $this->subject;
    }

    /**
     * sets subject
     * @param mixed
     * @return ModelInterface
     */
    public function setSubject($subject) : ModelInterface 
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * gets teachers
     * @return array
     */
    public function getTeachers() : ?array 
    {
        return $this->teachers;
    }

    /**
     * sets teachers
     * @param array
     * @return ModelInterface
     */
    public function setTeachers(array $teachers) : ModelInterface 
    {
        $this->teachers = $teachers;

        return $this;
    }

    /**
     * gets studentGroup
     * @return string
     */
    public function getStudentGroup() : ?string 
    {
        return $this->studentGroup;
    }

    /**
     * sets studentGroup
     * @param string
     * @return ModelInterface
     */
    public function setStudentGroup(string $studentGroup) : ModelInterface 
    {
        $this->studentGroup = $studentGroup;

        return $this;
    }

    /**
     * gets status
     * @return string
     */
    public function getStatus() : ?string 
    {
        return $this->status;
    }

    /**
     * sets status
     * @param string
     * @return ModelInterface
     */
    public function setStatus(string $status) : ModelInterface 
    {
        $this->status = $status;

        return $this;
    }

    /**
     * gets absenceReason
     * @return string
     */
    public function getAbsenceReason() : ?string 
    {
        return $this->absenceReason;
    }

    /**
     * sets absenceReason
     * @param string
     * @return ModelInterface
     */
    public function setAbsenceReason(string $absenceReason) : ModelInterface 
    {
        $this->absenceReason = $absenceReason;

        return $this;
    }

    /**
     * gets absentTime
     * @return int
     */
    public function getAbsentTime() : ?int 
    {
        return $this->absentTime;
    }

    /**
     * sets absentTime
     * @param int
     * @return ModelInterface
     */
    public function setAbsentTime(int $absentTime) : ModelInterface 
    {
        $this->absentTime = $absentTime;

        return $this;
    }

    /**
     * gets excuseStatus
     * @return string
     */
    public function getExcuseStatus() : ?string 
    {
        return $this->excuseStatus;
    }

    /**
     * sets excuse Status
     * @param string
     * @return ModelInterface
     */
    public function setExcuseStatus(string $excuseStatus) : ModelInterface 
    {
        $this->excuseStatus = $excuseStatus;

        return $this;
    }

    /**
     * returns user
     * @return string
     */
    public function getUser(): ?string 
    {
        return $this->user;
    }

    /**
     * sets user
     * @param string
     * @return ModelInterface
     */
    public function setUser(string $user) : ModelInterface 
    {
        $this->user = $user;

        return $this;
    }

    /**
     * returns checked
     * @return bool
     */
    public function getChecked(): ?bool 
    {
        return $this->checked;
    }

    /**
     * sets checked
     * @param bool
     * @return ModelInterface
     */
    public function setChecked(bool $checked) : ModelInterface
    {
        $this->checked = $checked;

        return $this;
    }

    /**
     * returns bool
     * @return string
     */
    public function getInvalid() : ?bool 
    {
        return $this->invalid;
    }

    /**
     * sets invalid
     * @param bool
     * @return ModelInterface
     */
    public function setInvalid(bool $invalid) : self 
    {
        $this->invalid = $invalid;

        return $this;
    }

    /**
     * return the startTime
     * @return \DateTime
     */
    public function getStartTime() : \DateTime 
    {
        return $this->startTime;
    }

    /**
     * sets the startTime
     * @param \DateTime $startTime
     * @return Period
     */
    public function setStartTime(\DateTime $startTime) : self 
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * return the endTime
     * @return \DateTime
     */
    public function getEndTime() : \DateTime 
    {
        return $this->endTime;
    }

    /**
     * sets the endTime
     * @param \DateTime $endTime
     * @return Period
     */
    public function setEndTime(\DateTime $endTime) : self 
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * sets an given field
     * @param string $field
     * @param mixed $value
     * @return Period
     */
    public function set(string $field, $value) : self
    {
        $this->$field = $value;

        return $this;
    }

    /**
     * loads the entity if its lazy loaded
     * @return self
     */
    public function load() : self
    {
        $query = new Query();

        if($this->student && !$this->student instanceof AbstractModel) {
            $this->student = $query->get('Students')->findBy(['key' => $this->student])[0];
        }

        if($this->subject && !$this->subject instanceof AbstractModel) {
           $this->subject = $query->get('Period')->findAll([], null, [
               'options' => [
                   'element' => $this->subject,
                   'type' => 3
               ]
           ])[0];
        }

        if(!empty($this->teachers) && $this->teachers[0] && !$this->teachers[0] instanceof AbstractModel) {
            $temp = [];
            foreach ($this->teachers as $value) {
                $temp[] = $query->get('Teachers')->findBy(['id' => $value]);
            }
            $this->teachers = $temp;
        }
        return $this;
    }
}