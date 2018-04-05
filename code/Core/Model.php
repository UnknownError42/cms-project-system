<?php
namespace Core;

use Core\Model\Collection;
use Core\Model\Manager;
use Core\Model\Query\Select;
use Core\Model\Query\Delete;
use Core\Model\Query\Update;

/**
 * Class Model
 * @package Core
 
 */
abstract class Model {

    /**
     * holds primary value
     *
     * @var
     */
    public $id;

    /**
     * holds columns info
     *
     * @var array
     */
    static protected $_columnInfo = array();

    /**
     * holds map of columns
     *
     * @var array
     */
    protected $_columnMap = array();

    /**
     * holds row status
     *
     * @var bool
     */
    protected $_isNew   = true;

    /**
     * holds values
     *
     * @var array
     */
    protected $_data    = array();

    /**
     * holds columns
     *
     * @var array
     */
    protected $_columns = array();

    /**
     *  holds primary column
     *
     * @var string
     */
    protected $_primary = 'id';

    /**
     * holds class name
     *
     * @var string
     */
    protected $_className;

    /**
     * holds cache status
     *
     * @var bool
     */
    protected $_cache = true;

    /**
     * holds order column
     *
     * @var string
     */
    protected $_orderColumn;

    /**
     * holds order direction
     *
     * @var string
     */
    protected $_orderDirection;

    /**
     * holds conditions
     *
     * @var array
     */
    protected $_conditions = array();

    /**
     * holds model table name
     *
     * @var string
     */
    public $_table;

    /**
     * holds model collection instance
     *
     * @var Collection
     */
    protected $_collection;

    /**
     * Model constructor.
     *
     *
     * @param bool $new
     */
    public function __construct($new = true) {

        $this->_isNew = $new;
        $this->initialize();

        foreach ($this as $key => $val) {
            if ($key[0] === '_') continue;
            $this->_columns[] = $key;
        }

        if ($this->_isNew === false) {
            foreach ($this->_columns as $column) {
                $this->_data[$column] = $this->$column;
            }
        }

        if ($new) {
            $this->_className = get_class($this);
        }
    }

    /**
     * Returns model collection
     *
     * @return Collection
     */
    public function getCollection() {
        if ($this->_collection === null) {
            $this->_collection = new Collection($this);
        }
        return $this->_collection;
    }

    /**
     * Returns model manager instance
     *
     * @return Manager
     */
    public function getManager() {
        return new Manager($this);
    }

    /**
     * Returns current class name
     *
     * @return string
     */
    public function getClassName() {
        return $this->_className;
    }

    /**
     * Returns PDO instance
     *
     * @return \Core\Db\Pdo
     */
    static public function getPDO() {
        return Di::get('db')->getPDO();
    }

    /**
     * Checks if row is new
     *
     * @return bool
     */
    public function isNew() {
        return $this->_isNew;
    }

    /**
     * Executes after construct
     *
     * @return $this
     */
    public function initialize() {
        return $this;
    }

    /**
     * Set custom order
     *
     * @param $column
     * @param string $direction
     * @return $this
     */
    public function setOrder($column, $direction = 'ASC') {
        $this->_orderColumn = $column;
        $this->_orderDirection = $direction;
        return $this;
    }

    /**
     * Check if ordering is set
     *
     * @return bool
     */
    public function hasOrder() {
        return ($this->_orderColumn !== null && $this->_orderDirection !== null);
    }

    /**
     * Set custom condition
     *
     * @param $key
     * @param $value
     * @param string $operator
     * @return $this
     */
    public function setCondition($key, $value, $operator = '=') {
        $this->_conditions[] = array(
            'key'       => $key,
            'value'     => $value,
            'operator'  => $operator
        );
        return $this;
    }

    /**
     * Excludes data
     *
     * @return array
     */
    public function excluded() {
        return array();
    }

    /**
     * Set data to row
     *
     * @param string|array $data
     * @param mixed $value
     * @return $this
     */
    public function setData($data, $value = null) {
        if (is_array($data)) {
            if ($this->_isNew === false) {
                if (isset($data[$this->_primary])) {
                    unset ($data[$this->_primary]);
                }
            }
            foreach ($this->_columns as $column) {
                if (isset($data[$column])) {
                    if ($this->$column !== $data[$column]) {
                        $this->$column = $data[$column];
                    }
                }
            }
        } else {
            foreach ($this->_columns as $column) {
                if ($column === $data) {
                    if ($this->$column !== $value) {
                        $this->$column = $value;
                        break;
                    }
                }
            }
        }
        return $this;
    }

    /**
     * Returns row data
     *
     * @return array
     */
    public function getData() {
        $data = array();
        foreach ($this->_columns as $column) {
            if ($this->_primary === null && $column === 'id') {
                continue;
            }
            $data[$column] = $this->$column;
        }
        return $data;
    }

    /**
     * Returns modified columns
     *
     * @return array
     */
    public function getModified() {
        $data = array();
        foreach ($this->_columns as $column) {
            if ($this->_data[$column] !== $this->$column) {
                $data[$column] = $this->$column;
            }
        }
        return $data;
    }


    /**
     * Find one row by value
     *
     * @param mixed $value
     * @param null|string $primary
     * @return bool|Model
     * @throws \Exception
     */
    public function findOne($value, $primary = null) {
        if ($primary !== null) {
            if (false === empty($this->_columns[$primary])) {
                var_dump($this->_columns);
                throw new \Exception("find one column " . $primary . " not found!");
            }
        }
        if (null === $primary) {
            $primary = $this->_primary;
        }
        $values = array();
        $select = new Select($this->_table);
        if (is_array($value)) {
            foreach ($value as $column => $val) {
                $select->where($column, '=', '?');
                $values[] = $val;
            }
        } else {
            $select->where($primary, '=', '?');
            $values[] = $value;
        }

        $select->query($values);

        try {
            $stmt = $select->execute();
            $result = $stmt->fetchObject($this->_className, array(false));
            return $result;
        } catch (\Exception $e) {
            echo "find one error";
            var_dump($e->getMessage());
            return false;
        }
    }

    /**
     * Find all rows
     *
     * @param null $start
     * @param null $limit
     * @return array
     */
    public function findAll($start = null, $limit = null) {
        $result = array();
        $params = array();
        $select = new Select($this->_table);

        if (count($this->_conditions) > 0) {
            foreach ($this->_conditions as $row) {
                $select->where($row['key'], $row['operator']);
                $params[] = $row['value'];
            }
        }

        if ($start !== null && $limit !== null) {
            $select->limit('?', '?');
            $params[] = $start;
            $params[] = $limit;
        }

        if ($this->hasOrder()) {
            $select->order($this->_orderColumn . ' ' . $this->_orderDirection);
        }

        $select->query($params);
        try {
            $stmt = $select->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_CLASS, $this->_className, array(false));

        } catch (\Exception $e) {
            echo "find all model error";
            var_dump($e->getMessage());
            echo $e->getTraceAsString();
        }
        return $result;
    }

    /**
     * Find all rows by custom columns
     *
     * @param $column
     * @param $value
     * @return array
     */
    public function findAllBy($column, $value) {
        $select = new Select($this->_table);
        $select->where($column, '=', '?');
        if ($this->hasOrder()) {
            $select->order($this->_orderColumn . ' ' . $this->_orderDirection);
        }
        $select->query(array($value));
        $result = array();
        try {
            $stmt = $select->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_CLASS, $this->_className, array(false));
        } catch (\Exception $e) {
            echo "find all model error";
            var_dump($e->getMessage());
            echo $e->getTraceAsString();
        }
        return $result;
    }

    /**
     * Find row by distinct column
     *
     * @param string $column
     * @return array
     */
    public function findDistinct($column = 'id') {
        $select = new Select($this->_table);
        $select->group($column);
        $select->query();
        $result = array();

        try {
            $stmt = $select->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_CLASS, $this->_className, array(false));
        } catch (\Exception $e) {
            echo "find all model error";
            var_dump($e->getMessage());
            echo $e->getTraceAsString();
        }
        return $result;
    }

    /**
     * Returns row count
     *
     * @param string $column
     * @param null $value
     * @return string
     */
    public function countRows($column = 'id', $value = null) {
        $values = array();
        $select = new Select($this->_table);
        $select->columns('COUNT('.$column.')');
        if (is_array($value)) {
            foreach ($value as $colName => $val) {
                $select->where($colName, '=', '?');
                $values[] = $val;
            }
        } else {
            if ($value !== null) {
                $select->where($column, '=', '?');
                $values[] = $value;
            }
        }

        $select->query($values);
        $count = 0;
        try {
            $stmt   = $select->execute();
            $count  = $stmt->fetchColumn();
        } catch (\Exception $e) {
            echo "count error: " . $e->getMessage();
        }
        return $count;
    }

    /**
     * Executes before save
     *
     * @return $this
     */
    protected function beforeSave() {
        return $this;
    }

    /**
     * Save record
     *
     * @return bool
     */
    public function save() {
        if ($this->_isNew === false) {
            return $this->update();
        }
        if (method_exists($this, 'beforeSave')) {
            if ($this->beforeSave() === false) {
                return false;
            }
        }
        $data = $this->getData();
        $result = $this->insert($data);
        return $result;
    }

    /**
     * Insert custom row
     *
     * @param array $data
     * @return bool
     */
    public function insert($data = array()) {
        $query = sprintf("INSERT INTO %s(%s) VALUES(%s)",
            $this->_table,
            implode(", ", array_keys($data)),
            implode(", ", array_fill(0, count($data), '?'))
        );

        try {
            $pdo    = $this->getPDO();
            $stmt   = $pdo->prepare($query);
            $result = $stmt->execute(array_values($data));

            if ($result === true) {
                $primary = $this->_primary;
                if ($primary !== null && false === isset($this->_columns[$primary])) {
                    $insertedID = $pdo->lastInsertId();
                    if ($primary === 'id') {
                        $this->id = (int)$insertedID;
                        unset($data[$primary]);
                    }
                }
                foreach ($data as $column => $value) {
                    $this->$column = $value;
                }
                return true;
            }
        } catch (\Exception $e) {
            echo "model insert error";
            var_dump($e->getMessage());
            echo $e->getTraceAsString();
        }
        return false;
    }

    /**
     * Checks if data has changed
     *
     * @param null $column
     * @return bool
     * @throws \Exception
     */
    public function hasChanged($column = null) {
        if ($column !== null) {
            if (false === isset($this->_data[$column])) {
                throw new \Exception("no changed column exists");
            }
            $default = $this->_data[$column];
            $current = $this->$column;
            if ($default !== $current) {
                return true;
            }
        } else {
            foreach ($this->_columns as $column) {
                $default = $this->_data[$column];
                $current = $this->$column;
                if ($default !== $current) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Executes before delete
     *
     * @return $this
     */
    protected function beforeUpdate() {
        return $this;
    }

    /**
     * Update custom rows
     *
     * @param null $columnValue
     * @param null $primary
     * @return bool
     */
    public function update($columnValue = null, $primary = null) {
        if ($primary === null) {
            foreach ($this->_columns as $column) {
                if ($this->_primary === $column) {
                    $primary = $column;
                    break;
                }
            }
        }

        $this->beforeUpdate();
        if ($columnValue === null) {
            if ($primary !== null) {
                $columnValue = $this->$primary;
            }
        }
        $data    = $this->getModified();
        if (count($data) === 0) {
            return true;
        }

        $result = false;
        $update = new Update($this->_table);
        $params = array_merge(array_values($data), array($columnValue));
        $update->set($data);
        $update->where($primary);
        try {
            $update->query($params);
            $result = $update->execute();
            if ($result) {
                foreach ($data as $column => $value) {
                    $this->$column = $value;
                }
            }
        } catch (\Exception $e) {
            echo "model update error";
            var_dump($e->getMessage());
            echo $e->getTraceAsString();
        }
        return $result;
    }

    /**
     * Execute before delete
     *
     * @return $this
     */
    protected function beforeDelete() {
        return $this;
    }

    /**
     * Delete custom rows
     *
     * @param string $column
     * @param null $value
     * @param string $operator
     * @return bool
     */
    public function delete($column = 'id', $value = null, $operator = '=') {
        if ($value === null) {
            $primary = $this->_primary;
            $value = $this->$primary;
        }
        $this->beforeDelete();
        $query = new Delete($this->_table);
        $query->where($column, $operator);
        $query->query(array($value));

        $result = $query->execute();
        return $result;
    }

    /**
     *
     *
     * @param int|array $id
     * @param string $column
     * @param $value
     * @return bool
     */
    public function updateColumn($id, $column, $value) {
        if (false === is_array($id)) {
            $id = array($id);
        }
        foreach ($id as $i) {
            if (false === ctype_digit($i)) {
                return false;
            }
        }
        $in     = implode(',', array_fill(0, count($id), '?'));
        $where  = 'id IN (' . $in . ')';
        $params = array_merge(array($value), $id);

        $update = new Update($this->_table);
        $update->set($column);
        $update->where($where, null, null);
        $update->query($params);
        $result = $update->execute();
        return $result;
    }

    public function transaction() {
        return $this->getPDO()->beginTransaction();
    }

    public function commit() {
        return $this->getPDO()->commit();
    }

    public function rollback() {
        return $this->getPDO()->rollBack();
    }
}