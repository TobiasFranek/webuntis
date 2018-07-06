<?php
declare(strict_types=1);

namespace Webuntis\Repositories;
use Webuntis\Models\Schoolyears;
use Webuntis\Models\CurrentSchoolyear;
use Webuntis\Handler\ExecutionHandler;

/**
 * SchoolyearsRepository
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class SchoolyearsRepository extends Repository {

    /**
     * return the current Schoolyear
     * @return object
     */
    public function getCurrentSchoolyear() {
        $this->model = CurrentSchoolyear::class;

        $data = $this->executionHandler->execute($this, []);

        return $data[0];
    }

    /**
     * {@inheritdoc}
     */
    public function parse(array $result) : array 
    {
        $data = $result;
        if ($this->model == CurrentSchoolyear::class) {
            $data = [$result];
            $this->model = Schoolyears::class;
        }
        return parent::parse($data);
    }
}