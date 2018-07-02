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


use Webuntis\Models\Interfaces\CachableModelInterface;
use JMS\Serializer\Annotation\SerializedName;


/**
 * Class Holidays
 * @package Webuntis\Models
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class Holidays extends AbstractModel implements CachableModelInterface {

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
     * @var int
     */
    const CACHE_LIFE_TIME = 86400;

    /**
     * @var string
     */
    const METHOD = 'getHolidays';

    /**
     * returns the name
     * @return string
     */
    public function getName() : string 
    {
        return $this->name;
    }

    /**
     * sets the name
     * @param string $name
     * @return Holidays
     */
    public function setName(string $name) : self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * returns the fullName
     * @return string
     */
    public function getFullName() : string 
    {
        return $this->fullName;
    }

    /**
     * set the fullName
     * @param string $fullName
     * @return Holidays
     */
    public function setFullName(string $fullName) : self 
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * return the startDate
     * @return \DateTime
     */
    public function getStartDate() : \DateTime 
    {
        return $this->startDate;
    }

    /**
     * sets the startDate
     * @param \DateTime $date
     * @return Holidays
     */
    public function setStartDate(\DateTime $date) : self 
    {
        $this->startDate = $date;

        return $this;
    }

    /**
     * return the endDate
     * @return \DateTime
     */
    public function getEndDate() : \DateTime 
    {
        return $this->endDate;
    }

    /**
     * sets the endDate
     * @param \DateTime $date
     * @return Holidays
     */
    public function setEndDate(\DateTime $date) : self 
    {
        $this->endDate = $date;

        return $this;
    }
    /**
     * sets an given field
     * @param string $field
     * @param mixed $value
     * @return Holidays
     */
    public function set(string $field, $value) : self
    {
        $this->$field = $value;

        return $this;
    }
}