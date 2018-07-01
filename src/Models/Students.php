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

use Webuntis\Configuration\YAMLConfiguration;
use Webuntis\Models\Interfaces\AdministrativeModelInterface;
use Webuntis\Models\Interfaces\CachableModelInterface;
use Webuntis\Types\StringType;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class User
 * @package Webuntis\Models
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class Students extends AbstractModel implements AdministrativeModelInterface, CachableModelInterface {
    /**
     * @var string
     */
    private $name;

    /**
     * @SerializedName("firstName")
     * @var string
     */
    private $firstName;

    /**
     * @SerializedName("lastName")
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $gender;

    /***
     * @var int
     */
    const CACHE_LIFE_TIME = 86400;

    /**
     * @var string
     */
    const METHOD = "getStudents";

    /**
     * return the name
     * @return string
     */
    public function getName() : string 
    {
        return $this->name;
    }

    /**
     * sets the name
     * @param string $name
     * @return Students
     */
    public function setName(string $name) : self 
    {
        $this->name = $name;

        return $this;
    }

    /**
     * return the firstName
     * @return string
     */
    public function getFirstName() : string 
    {
        return $this->firstName;
    }

    /**
     * set the firstName
     * @param string $firstName
     * @return Students
     */
    public function setFirstName(string $firstName) : self 
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * get the lastName
     * @return string
     */
    public function getLastName() : string 
    {
        return $this->lastName;
    }

    /**
     * sets the lastName
     * @param string $lastName
     * @return Students 
     */
    public function setLastName(string $lastName) : self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * returns the gender
     * @return string
     */
    public function getGender() : string 
    {
        return $this->gender;
    }

    /**
     * sets the gender
     * @param string $gender
     * @return Students
     */
    public function setGender(string $gender) : self 
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * returns the key
     * @return string
     */
    public function getKey() : string 
    {
        return $this->key;
    }

    /**
     * sets the key
     * @param string $key
     * @return Students
     */
    public function setKey(string $key) : self 
    {
        $this->key = $key;

        return $this;
    }

    /**
     * sets an given field
     * @param string $field
     * @param mixed $value
     * @return Students
     */
    public function set(string $field, $value) : self {
        $this->$field = $value;

        return $this;
    }
}