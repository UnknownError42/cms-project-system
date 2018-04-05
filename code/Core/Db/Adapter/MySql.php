<?php
namespace Core\Db\Adapter;

use Core\Db\Adapter;
use Core\Db\Pdo;

/**
 * Class MySql
 * @package Core\Db\Adapter
 
 */
class MySql extends Adapter {

    /**
     * Returns PDO instance
     *
     * @return Pdo
     */
    public function getPDO() {
        $dns = sprintf('mysql:host=%s;dbname=%s', $this->_options['host'], $this->_options['db']);
        return new Pdo($dns, $this->_options['user'], $this->_options['pass'], array(
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ
        ));
    }
}