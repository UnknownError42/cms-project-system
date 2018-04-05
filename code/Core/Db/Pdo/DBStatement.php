<?php
/**
 * Copyright (c) 2018.  MyArtSide 
 */

namespace Core\Db\Pdo;

use Core\Db\Pdo;

/**
 * Class DBStatement
 * @package Core\Db\Pdo
 
 */
class DBStatement extends \PDOStatement {

    /** @var Pdo */
    public $dbh;

    public function __construct(Pdo $dbh, $props = array()) {
        $this->dbh = $dbh;
        foreach ($props as $key => $val) {
            #echo $key;
           # exit;
        }
    }
}