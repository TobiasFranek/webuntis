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

namespace Webuntis\Types;


use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Webuntis\Models\AbstractModel;
use Webuntis\Types\Interfaces\TypeInterface;

/**
 * Class StringType
 * @package Webuntis\Types
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class StringType implements TypeInterface {

    /**
     * executes an certain parsing part
     * @param AbstractModel $model
     * @param $data
     * @param $field
     */
    public static function execute(AbstractModel &$model, $data, $field) {
        $fieldName = array_keys($field)[0];
        if(isset($data[$field[$fieldName]['api']['name']])) {
            $model->set($fieldName, $data[$field[$fieldName]['api']['name']]);
        }
    }

    /**
     * asks for the params according to the type and return an array with the field information
     * @param OutputInterface $output
     * @param InputInterface $input
     * @param $helper
     * @return array
     */
    public static function generateTypeWithConsole(OutputInterface $output, InputInterface $input, $helper) {
        $question = new Question('API name: ');
        $apiName = $helper->ask($input, $output, $question);
        return [
            'type' => self::getName(),
            'api' => [
                'name' => $apiName
            ]
        ];
    }

    /**
     * return name of the type
     * @return string
     */
    public static function getName() {
        return 'string';
    }

    /**
     * return type of the Type Class
     * @return string
     */
    public static function getType() {
        return 'string';
    }
}