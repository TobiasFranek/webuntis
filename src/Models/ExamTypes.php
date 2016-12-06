<?php
/**
 * Created by PhpStorm.
 * Author: Tobias Franek <tobias.franek@gmail.com>
 * Project: webuntis
 * Date: 06.12.16
 * Time: 19:02
 */

namespace Webuntis\Models;


use Webuntis\Models\Interfaces\AdministrativeModelInterface;
use Webuntis\Models\Interfaces\CachableModelInterface;

class ExamTypes extends AbstractModel implements CachableModelInterface, AdministrativeModelInterface {

    /**
     * @var int
     */
    private $type;

    /**
     * @var string
     */
    const METHOD = 'getExamTypes';

    /**
     * @param $data
     */
    public function parse($data) {
        $this->setId($data['id']);
        $this->type = $data['type'];
    }

    /**
     * @return array
     */
    public function serialize() {
        return [
            'id' => $this->getId(),
            'type' => $this->type
        ];
    }
}