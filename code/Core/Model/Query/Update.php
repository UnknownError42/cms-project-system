<?php
namespace Core\Model\Query;

use Core\Db;
use Core\Di;

class Update {

    protected $_table;
    protected $_where = array();

    protected $_query;
    protected $_data = array();
    protected $_params = array();

    /**
     *
     *
     * Select constructor.
     * @param null $table
     */
    public function __construct($table = null) {
        $this->_table = $table;
    }

    /**
     *
     *
     * @param $query
     * @return $this
     */
    public function setQuery($query) {
        $this->_query = $query;
        return $this;
    }

    /**
     *
     *
     * @param array $params
     * @return $this
     */
    public function setParams($params = array()) {
        $this->_params = $params;
        return $this;
    }

    /**
     *
     *
     * @param $table
     * @return $this
     */
    public function from($table) {
        $this->_table = $table;
        return $this;
    }

    /**
     *
     *
     * @param $key
     * @param string $value
     * @return $this
     */
    public function set($key, $value = '?') {
        if (is_array($key)) {
            foreach (array_keys($key) as $column) {
                $this->set($column, $value);
            }
        } else {
            $this->_data[] = array(
                'column'    => $key,
                'operator'  => '=',
                'value'     => $value
            );
        }
        return $this;
    }

    /**
     *
     *
     * @param $key
     * @param $operator
     * @param $value
     * @param null $type
     * @return $this
     */
    protected function _addWhere($key, $operator, $value, $type = null) {
        $this->_where[] = array(
            'key'           => $key,
            'operator'      => $operator,
            'value'         => $value,
            'type'          => $type
        );
        return $this;
    }

    /**
     *
     *
     * @param $key
     * @param string $operator
     * @param string $value
     * @return $this
     */
    public function where($key, $operator = '=', $value = '?') {
        return $this->_addWhere($key, $operator, $value);
    }

    /**
     *
     *
     * @param $where
     * @return $this
     */
    public function plainWhere($where) {
        return $this->_addWhere($where, null, null, null);
    }

    /**
     *
     *
     * @param $key
     * @param string $operator
     * @param string $value
     * @return $this
     */
    public function andWhere($key, $operator = '=', $value = '?') {
        return $this->_addWhere($key, $operator, $value, 'AND');
    }

    /**
     *
     *
     * @param $key
     * @param string $operator
     * @param string $value
     * @return $this
     */
    public function orWhere($key, $operator = '=', $value = '?') {
        return $this->_addWhere($key, $operator, $value, 'OR');
    }

    /**
     *
     *
     * @param array $params
     * @return $this
     */
    public function query($params = array()) {
        $this->_query = $this->build();
        $this->_params = $params;
        return $this;
    }

    /**
     *
     *
     * @return string
     */
    public function getHash() {
        return 'select ' . hash('md5', $this->_query . print_r($this->_params, true));
    }

    /**
     *
     *
     * @return \PDOStatement
     */
    public function execute() {
        $pdo = Di::get('db')->getPDO();
        $stmt = $pdo->prepare($this->_query);
        $stmt->execute($this->_params);
        return $stmt;
    }

    public function getParams() {
        return $this->_params;
    }

    /**
     *
     *
     * @return string
     */
    public function build() {
        $query = 'UPDATE ' . $this->_table . ' SET ';

        $set = array();
        foreach ($this->_data as $arr) {
            $set[] = implode(" ", array_values($arr));
        }
        $query .= implode(", ", $set);

        if (count($this->_where) > 0) {
            $query .= ' WHERE ';
            $where = array();
            foreach ($this->_where as $arr) {
                $where[] = implode(" ", array_values($arr));
            }
            $query .= implode(" AND ", $where) . ' ';
        }

        return trim($query);
    }
}