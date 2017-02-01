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


namespace Webuntis\Models;

use Webuntis\Exceptions\ModelException;
use Webuntis\Models\Interfaces\AdministrativeModelInterface;
use Webuntis\Query\Query;
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
     * @var Subjects[]
     */
    private $subject = [];

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
    public function getClasses() {
        return $this->classes;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate() {
        return $this->endDate;
    }

    /**
     * @return Students[]
     */
    public function getStudents() {
        return $this->students;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate() {
        return $this->startDate;
    }

    /**
     * @return Teachers[]
     */
    public function getTeachers() {
        return $this->teachers;
    }

    /**
     * @return Subjects[]
     */
    public function getSubject() {
        return $this->subject;
    }

    /**
     * @param Classes[] $classes
     */
    public function setClasses($classes) {
        $this->classes = $classes;
    }

    /**
     * @param \DateTime $endDate
     */
    public function setEndDate($endDate) {
        $this->endDate = $endDate;
    }

    /**
     * @param \DateTime $startDate
     */
    public function setStartDate($startDate) {
        $this->startDate = $startDate;
    }

    /**
     * @param Students[] $students
     */
    public function setStudents($students) {
        $this->students = $students;
    }

    /**
     * @param Subjects[] $subject
     */
    public function setSubject($subject) {
        $this->subject = $subject;
    }

    /**
     * @param Teachers[] $teachers
     */
    public function setTeachers($teachers) {
        $this->teachers = $teachers;
    }

    /**
     * return the children by given id
     * @param $key
     * @return AbstractModel[]
     */
    public function get($key) {
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
     * @param $field
     * @param $value
     */
    public function set($field, $value) {
        $this->$field = $value;
    }
}