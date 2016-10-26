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


use Webuntis\Models\Interfaces\CachableModelInterface;

/**
 * Class Holidays
 * @package Webuntis\Models
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class Holidays extends Model implements CachableModelInterface {

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $fullName;

    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var \DateTime
     */
    private $endDate;

    /**
     * @var string
     */
    const METHOD = 'getHolidays';

    /**
     * parses the given data from the json rpc api to the right format for the object
     * @param array $data
     */
    protected function parse($data) {
        $this->setId($data['id']);
        $this->name = $data['name'];
        $this->fullName = $data['longName'];
        $this->startDate = new \DateTime($data['startDate']);
        $this->endDate = new \DateTime($data['endDate']);
    }

    /**
     * serializes the object and returns an array with the objects values
     * @return array
     */
    public function serialize() {
        return [
            'id' => $this->getId(),
            'name' => $this->name,
            'fullName' => $this->fullName,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate
        ];
    }

    /**
     * returns the name
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * sets the name
     * @param string $name
     * @return $this
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
     * @return Holidays $this
     */
    public function setFullName($fullName) {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * return the startDate
     * @return \DateTime
     */
    public function getStartDate() {
        return $this->startDate;
    }

    /**
     * sets the startDate
     * @param \DateTime $date
     * @return Holidays $this
     */
    public function setStartDate(\DateTime $date) {
        $this->startDate = $date;

        return $this;
    }

    /**
     * return the endDate
     * @return \DateTime
     */
    public function getEndDate() {
        return $this->endDate;
    }

    /**
     * sets the endDate
     * @param \DateTime $date
     * @return Holidays $this
     */
    public function setEndDate(\DateTime $date) {
        $this->endDate = $date;
        return $this;
    }

}