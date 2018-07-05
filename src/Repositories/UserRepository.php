<?php
declare(strict_types=1);

namespace Webuntis\Repositories;

use Webuntis\WebuntisFactory;

/**
 * UserRepository
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class UserRepository {

    /**
     * return the current logged in User of the default instance
     * @return \Webuntis\Models\AbstractModel
     */
    public function getCurrentUser() : object 
    {
        $instance = WebuntisFactory::create();
        return $instance->getCurrentUser();
    }

    /**
     * returns the current user type of the currently logged in User of the default instance
     * @return int
     */
    public function getCurrentUserType() : int 
    {
        $instance = WebuntisFactory::create();
        return $instance->getCurrentUserType();
    }
}