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


use Webuntis\Models\Interfaces\AdministrativeModelInterface;
use Webuntis\Models\Interfaces\CachableModelInterface;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class ExamTypes
 * @package Webuntis\Models
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class ExamTypes extends AbstractModel implements CachableModelInterface, AdministrativeModelInterface {

    /**
     * @var int
     */
    private $type;

    /**
     * @var string
     */
    private $name;

    /**
     * @SerializedName("longName")
     * @var string
     */
    private $longName;

    /**
     * @var string
     */
    const METHOD = 'getExamTypes';

    /**
     * @var int
     */
    const CACHE_LIFE_TIME = 0;

    /**
     * @return int
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getLongName() {
        return $this->longName;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $longName
     */
    public function setLongName($longName) {
        $this->longName = $longName;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
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