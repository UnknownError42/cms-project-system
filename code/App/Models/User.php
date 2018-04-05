<?php
/**
 * Copyright (c) 2018.  MyArtSide 
 */

namespace App\Models;

use Core\Model;

class User extends Model {

    public $id;
    public $name;
    public $password;
    public $access;
    public $fullname;
    public $email;
    public $dburl;
    public $_table = 'users';

    /**
     *
     *
     * @param $name
     * @return User
     * @throws \Exception
     */
    static public function getUserByName($name) {
        $user = new User();
        return $user->findOne($name, 'name');
    }

    public function getId() {
        return (int)$this->id;
    }

    /**
     *
     *
     * @return bool
     */
    public function displayDbUrl() {
        return (1 === (int)$this->dburl);
    }

    /**
     *
     *
     * @return bool
     */
    public function isAdmin() {
        return ($this->access === '2');
    }
}