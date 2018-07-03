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

namespace Webuntis\Repositories;
use Webuntis\Models\Schoolyears;
use Webuntis\Models\CurrentSchoolyear;
use Webuntis\Util\ExecutionHandler;

/**
 * Class SchoolyearsRepository
 * @package Webuntis\Repositories
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class SchoolyearsRepository extends Repository {
    /**
     * return the parsed Schoolyear object
     * @param array $result
     * @return Schoolyear
     */
    // public function parse(array $result) : object 
    // {
    //     return new $this->model($result);
    // }

    /**
     * return the current Schoolyear
     * @return object
     */
    public function getCurrentSchoolyear() {
        $this->model = CurrentSchoolyear::class;

        $data = ExecutionHandler::execute($this, []);

        return $data[0];
    }

    /**
     * {@inheritdoc}
     */
    public function parse(array $result) : array 
    {
        $data = $result;
        if($this->model == CurrentSchoolyear::class) {
            $data = [$result];
            $this->model = Schoolyears::class;
        }
        return parent::parse($data);
    }
}