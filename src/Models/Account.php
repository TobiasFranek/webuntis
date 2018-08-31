<?php
declare(strict_types=1);

namespace Webuntis\Models;

/**
 * Account Model
 * This class is somekind of fallback for the auth flow
 * it has only the field that are needed for a simple authentication
 * @author Tobias Franek <tobias.franek@gmail.com>
 * @license MIT
 */
class Account {

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $userType;

    /**
     * @param int $id 
     * @param int $userType
     */
    public function __construct($id, $userType) {
        $this->id = $id;
        $this->userType = $userType;
    }


    /**
     * sets the userType
     * @param int $userType
     * @return Account
     */
    public function setUserType(int $userType) : self 
    {
        $this->userType = $userType;

        return $this;
    }

    /**
     * sets the id of the Account
     * @param int $id 
     * @return Account
     */
    public function setId(int $id) : self 
    {
        $this->id = $id;

        return $this;
    }

    /**
     * get the userType
     * @return int
     */
    public function getUserType() : int 
    {
        return $this->userType;
    }

    /**
     * get the id
     * @return int
     */
    public function getId() : int 
    {
        return $this->id;
    }


    /**
     * sets an given field
     * @param string $field
     * @param mixed $value
     * @return Account
     */
    public function set(string $field, $value) : self 
    {
        $this->$field = $value;

        return $this;
    }
}