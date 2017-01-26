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
 * Class Classes
 * @package Webuntis\Models
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class Classes extends AbstractModel implements CachableModelInterface {

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $fullName;

    /**
     * @var int
     */
    const CACHE_LIFE_TIME = 86400;

    /**
     * @var string
     */
    const METHOD = 'getKlassen';
    

    /**
     * parses the given data from the json rpc api to the right format for the object
     * @param array $data
     */
    protected function parse($data) {
        $this->setId($data['id']);
        $this->name = $data['name'];
        $this->fullName = $data['longName'];
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
     * @return Classes $this
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
     * @return Classes $this
     */
    public function setFullName($fullName) {
        $this->fullName = $fullName;

        return $this;
    }
}