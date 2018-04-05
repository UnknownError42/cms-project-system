<?php
/**
 * Copyright (c) 2018.  MyArtSide 
 */

namespace Core\Db;

/**
 * Class Pdo
 * @package Core\Db
 
 */
class Pdo extends \PDO {

    /**
     * Pdo constructor.
     *
     *
     * @param string $dsn
     * @param null|string $username
     * @param null|string $password
     * @param null|array $options
     */
    public function __construct($dsn, $username = null, $password = null, $options = null) {
        parent::__construct($dsn, $username, $password, $options);
        $this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
    }
}