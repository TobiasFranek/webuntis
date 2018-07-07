<?php
declare(strict_types=1);

namespace Webuntis\Security\Interfaces;


/**
 * Interface for the Secruity Manager
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
interface SecurityManagerInterface {

    /**
     * returns a client of an specific context
     * @return object
     */
    public function getClient() : object;

    /**
     * returns the id of the current user
     * @return int
     */
    public function getCurrentUserId() : int;

    /**
     * return the type of the current user
     * @return int
     */
    public function getCurrentUserType() : int;

    /**
     * return how the config should be given
     * is used by the commands
     * @return array
     */
    public static function getConfigMeta() : array;

}