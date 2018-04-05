<?php
namespace Core\Model\Query;

use Core\Db;
use Core\Di;

class Select {

    protected $_table;
    protected $_columns = '*';
    protected $_joins = array();
    protected $_where = array();
    protected $_order = null;
    protected $_group = null;
    protected $_limit;
    protected $_offset;

    protected $_query;
    protected $_params = array();

    /**
     *
     *
     * Select constructor.
     * @param null $table
     * @param null $as
     */
    public function __construct($table = null, $as = null) {
        if ($as !== null) {
            $table .= ' ' . $as;
        }
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
     * @param $columns
     * @return $this
     */
    public function columns($columns) {
        $this->_columns = $columns;
        return $this;
    }

    /**
     *
     *
     * @param $order
     * @return $this
     */
    public function order($order) {
        $this->_order = $order;
        return $this;
    }

    /**
     *
     *
     * @param $column
     * @return $this
     */
    public function group($column) {
        $this->_group = $column;
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
     * @param $table
     * @param $direction
     * @param null $condition
     * @return $this
     */
    protected function _join($table, $direction, $condition = null) {
        $this->_joins[] = array(
            'table'     => $table,
            'direction' => $direction,
            'condition' => $condition);
        return $this;
    }

    /**
     *
     *
     * @param $table
     * @param null $condition
     * @return Select
     */
    public function leftJoin($table, $condition = null) {
        return $this->_join($table, 'LEFT JOIN', $condition);
    }

    /**
     *
     *
     * @param $limit
     * @param null $offset
     * @return $this
     */
    public function limit($limit, $offset = null) {
        $this->_limit = $limit;
        $this->_offset = $offset;
        return $this;
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
        $query = 'SELECT ' . $this->_columns . ' FROM ' . $this->_table . ' ';

        if (count($this->_joins) > 0) {
            $joins = array();
            foreach ($this->_joins as $arr) {
                $join = array($arr['direction'], $arr['table']);
                if ($arr['condition'] !== null) {
                    $join[] = 'ON';
                    $join[] = $arr['condition'];
                }
                $joins[] = implode(" ", $join);
            }
            $query .= implode(" ", $joins) . ' ';
        }

        if (count($this->_where) > 0) {
            $query .= 'WHERE ';
            $where = array();
            foreach ($this->_where as $arr) {
                $values =  array_values($arr);
                $values = array_map('trim', $values);
                $where[] = implode(" ", $values);
            }
            $query .= implode(" AND ", $where) . ' ';
        }

        if ($this->_group !== null) {
            $query .= 'GROUP BY ' . $this->_group . ' ';
        }

        if ($this->_order !== null) {
            $query .= 'ORDER BY ' . $this->_order . ' ';
        }

        // limit
        if ($this->_limit !== null) {
            $additional = 'LIMIT ';
            if ($this->_offset !== null) {
                $additional .= $this->_offset . ',';
            }
            $additional .= $this->_limit;
            $query .= $additional . ' ';
        }

        return $query;
    }
}