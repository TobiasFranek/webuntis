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
use Webuntis\Query\Query;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class ClassHasTeachers
 * @package Webuntis\Models
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class ClassHasTeachers extends AbstractModel {
    /**
     * @var string
     */
    private $name;

    /**
     * @SerializedName("fullName")
     * @var string
     */
    private $fullName;
    /**
     * @var Teachers[]
     */
    private $teachers = [];

    /**
     * returns the Teachers
     * @return Teachers[]
     */
    public function getTeachers() {
        return $this->teachers;
    }

    /**
     * sets the Teachers
     * @param Teachers[] $teachers
     * @return $this
     */
    public function setTeachers(array $teachers) {
        $this->teachers = $teachers;

        return $this;
    }
    /**
     * returns the name
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * set the name
     * @param string $name
     * @return ClassHasTeachers $this
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * returns the fullName
     * @return string
     */
    public function getFullName() {
        return $this->fullName;
    }

    /**
     * set the fullName
     * @param string $fullName
     * @return ClassHasTeachers $this
     */
    public function setFullName($fullName) {
        $this->fullName = $fullName;

        return $this;
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