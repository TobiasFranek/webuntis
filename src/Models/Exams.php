<?php
declare(strict_types=1);

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


namespace Webuntis\Models;

use Webuntis\Exceptions\ModelException;
use Webuntis\Models\Interfaces\AdministrativeModelInterface;
use Webuntis\Query\Query;
use Webuntis\Models\Interfaces\ModelInterface;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class Exams
 * @package Webuntis\Models
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class Exams extends AbstractModel implements AdministrativeModelInterface {

    /**
     * @var Classes[]
     */
    private $classes = [];

    /**
     * @var Teachers[]
     */
    private $teachers = [];

    /**
     * @var Students[]
     */
    private $students = [];

    /**
     * @var Subjects
     */
    private $subject;

    /**
     * @SerializedName("startDate")
     * @var \DateTime
     */
    private $startDate;

    /**
     * @SerializedName("endDate")
     * @var \DateTime
     */
    private $endDate;

    /**
     * @var string
     */
    const METHOD = 'getExams';

    /**
     * @return Classes[]
     */
    public function getClasses() : array 
    {
        return $this->classes;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate() : \DateTime
    {
        return $this->endDate;
    }

    /**
     * @return Students[]
     */
    public function getStudents() : array 
    {
        return $this->students;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate() : \DateTime 
    {
        return $this->startDate;
    }

    /**
     * @return Teachers[]
     */
    public function getTeachers() : array 
    {
        return $this->teachers;
    }

    /**
     * @return Subjects
     */
    public function getSubject() : ModelInterface
    {
        return $this->subject;
    }

    /**
     * @param Classes[] $classes
     * @return Exams
     */
    public function setClasses(array $classes) : self 
    {
        $this->classes = $classes;

        return $this;
    }

    /**
     * @param \DateTime $endDate
     * @return Exams
     */
    public function setEndDate(\DateTime $endDate) : self 
    {
        $this->endDate = $endDate;
        
        return $this; 
    }

    /**
     * @param \DateTime $startDate
     * @return Exams
     */
    public function setStartDate(\DateTime $startDate) : self 
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @param Students[] $students
     * @return Exams
     */
    public function setStudents(array $students) : self 
    {
        $this->students = $students;
    
        return $this;
    }

    /**
     * @param Subjects $subject
     * @return Exams
     */
    public function setSubject(ModelInterface $subject) : self 
    {
        $this->subject = $subject;
    
        return $this;
    }

    /**
     * @param Teachers[] $teachers
     * @return Exams
     */
    public function setTeachers(array $teachers) : self 
    {
        $this->teachers = $teachers;
    
        return $this;
    }

    /**
     * return the children by given id
     * @param string $key
     * @return AbstractModel[]
     * @throws ModelException
     */
    public function get(string $key) : array 
    {
        switch ($key) {
            case 'teachers':
                return $this->teachers;
            case 'students':
                return $this->students;
            case 'classes':
                return $this->classes;
            case 'subject':
                return $this->subject;
            default:
                throw new ModelException('array of objects' . $key . 'doesn\'t exist');
        }
    }
    /**
     * sets an given field
     * @param string $field
     * @param mixed $value
     * @return Exams
     */
    public function set(string $field, $value) : self 
    {
        $this->$field = $value;

        return $this;
    }
}