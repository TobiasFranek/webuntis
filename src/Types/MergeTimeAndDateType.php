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


use Webuntis\Models\AbstractModel;
use Webuntis\Types\Interfaces\TypeInterface;

/**
 * Class MergeTimeAndDateType
 * @package Webuntis\Types
 * @author Tobias Franek <tobias.franek@gmail.com>
 */
class MergeTimeAndDateType implements TypeInterface {

    /**
     * executes an certain parsing part
     * @param AbstractModel $model
     * @param $data
     * @param $field
     */
    public static function execute(AbstractModel &$model, $data, $field) {
        $fieldName = array_keys($field)[0];
        $fieldValues = $field[$fieldName];
        if (isset($data[$fieldValues['api']['time']])) {
            if (strlen($data[$fieldValues['api']['time']]) < 4) {
                $model->set($fieldName, new \DateTime($data[$fieldValues['api']['date']] . ' ' . '0' . substr($data[$fieldValues['api']['time']], 0, 1) . ':' . substr($data[$fieldValues['api']['time']], strlen($data[$fieldValues['api']['time']]) - 2, strlen($data[$fieldValues['api']['time']]))));
            } else {
                $model->set($fieldName, new \DateTime($data[$fieldValues['api']['date']] . ' ' . substr($data[$fieldValues['api']['time']], 0, 2) . ':' . substr($data[$fieldValues['api']['time']], strlen($data[$fieldValues['api']['time']]) - 2, strlen($data[$fieldValues['api']['time']]))));
            }
        }
    }

    /**
     * return name of the type
     * @return string
     */
    public static function getName() {
        return 'mergeTimeAndDate';
    }

    /**
     * return type of the Type Class
     * @return string
     */
    public static function getType() {
        return \DateTime::class;
    }
}