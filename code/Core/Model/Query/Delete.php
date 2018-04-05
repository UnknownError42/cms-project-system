<?php
namespace Core\Model\Query;

use Core\Db;
use Core\Di;

class Delete {

    protected $_table;
    protected $_query;
    protected $_params = array();
    protected $_where = array();

    public function __construct($table) {
        $this->_table = $table;
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
        $this->_where[] = array($key, $operator, $value);
        return $this;
    }

    /**
     *
     *
     * @param $key
     * @param array $value
     * @return $this
     */
    public function whereIn($key, $value = array()) {
        $in = implode(',', array_fill(0, count($value), '?'));
        $this->_where[] = array($key, 'IN('.$in.')');
        return $this;
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
    public function build() {
        $query = array('DELETE FROM', $this->_table);
        if (count($this->_where) > 0) {
            $query[] = 'WHERE';
            $where = array();
            foreach ($this->_where as $arr) {
                $where[] = implode(" ", array_values($arr));
            }
            $query[] = implode(", ", $where);
        }
        return implode(" ", $query);
    }
}