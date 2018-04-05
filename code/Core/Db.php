<?php
namespace Core;

use Core\Db\Pdo;

/**
 * Class Db
 * @package Core
 
 */
class Db {

    static protected $_instance = null;

    /**
     * holds all connection instances
     *
     * @var array
     */
    protected $_connections = array();

    /**
     * holds options
     *
     * @var array
     */
    protected $_options;

    /**
     * Db constructor.
     *
     * @param array $options
     */
    public function __construct($options = array()) {
        $this->setOptions($options);
    }

    /**
     * Set options
     *
     * @param array $options
     * @return $this
     */
    public function setOptions($options = array()) {
        $this->_options = $options;
        return $this;
    }

    /**
     * Returns singleton instance
     *
     * @return Db
     */
    static public function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new Db();
        }
        return self::$_instance;
    }

    /**
     * Returns pdo instance
     *
     * @param string $name
     * @return Pdo
     */
    public function getPDO($name = 'default') {
        if ($this->getConnection($name) === false) {
            if (isset($this->_options[$name])) {
                $config = $this->_options[$name];
                $class   = $config['adapter'];
                $adapter = $this->getAdapter($class, $config);
                $this->addConnection($name, $adapter->getPDO());
            }
        }
        return $this->getConnection($name);
    }

    /**
     * Returns adapter instance
     *
     * @param $class
     * @param array $options
     * @return \Core\Db\Adapter
     */
    public function getAdapter($class, $options = array()) {
        $adapter = new $class($options);
        return $adapter;
    }

    /**
     * Check if table exists
     *
     * @param $table
     * @return bool
     */
    public function tableExists($table) {
        $test = sprintf("SELECT name FROM sqlite_master WHERE type = 'table' AND name = '%s';", $table);
        echo $test;
        $stmt = $this->getPDO()->query($test);
        return $stmt->execute();
    }

    /**
     * Register connection
     *
     * @param $name
     * @param Pdo $connection
     * @return $this
     */
    protected function addConnection($name, $connection) {
        $this->_connections[$name] = $connection;
        return $this;
    }

    /**
     * Returns connection
     *
     * @param $name
     * @return Pdo
     */
    protected function getConnection($name) {
        if (isset($this->_connections[$name])) {
            return $this->_connections[$name];
        }
        return false;
    }
}