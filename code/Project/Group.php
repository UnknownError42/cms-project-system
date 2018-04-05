<?php
/**
 * Copyright (c) 2018.  MyArtSide 
 */

namespace Project;

use App\Models\User;

class Group {

    public $name;

    protected $_user;

    public function __construct($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function getLabel() {
        if (($user = $this->getUser())) {
            return $user->fullname;
        }
        return $this->name;
    }

    /**
     *
     *
     * @return User
     */
    public function getUser() {
        if ($this->_user === null) {
            $this->_user = User::getUserByName($this->name);
        }
        return $this->_user;
    }
}