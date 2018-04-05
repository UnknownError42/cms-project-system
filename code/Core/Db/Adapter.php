<?php
namespace Core\Db;

/**
 * Class Adapter
 * @package Core\Db
 
 */
abstract class Adapter {

    /**
     * Holds adapter options
     *
     * @var array
     */
    protected $_options = array();

    /**
     * Returns PDO instance
     *
     * @return \Core\Db\Pdo
     */
    abstract public function getPDO();

    /**
     * Adapter constructor.
     *
     * @param array $options
     */
    public function __construct($options = array()) {
        $this->_options = $options;
    }
}