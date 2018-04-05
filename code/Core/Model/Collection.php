<?php
namespace Core\Model;

use Core\Model;

class Collection {

    protected $_model;

    protected $_conditions  = array();

    protected $_limit       = null;

    protected $_offset      = null;

    protected $_order       = array();

    public function __construct(Model $model) {
        $this->_model = $model;
    }

    public function addCondition($key, $value, $operator) {
        $this->_conditions[] = array(
            'key'       => $key,
            'value'     => $value,
            'operator'  => $operator
        );
        return $this;
    }

    /**
     * Set asc order
     *
     * @param $column
     * @return Collection
     */
    public function setAscOrder($column) {
        return $this->setOrder($column, 'ASC');
    }

    /**
     * Set desc order
     *
     * @param $column
     * @return Collection
     */
    public function setDescOrder($column) {
        return $this->setOrder($column, 'DESC');
    }

    /**
     * Set custom order
     *
     * @param $column
     * @param $direction
     * @return $this
     */
    protected function setOrder($column, $direction) {
        $this->_order[] = array(
            'column'        => $column,
            'direction'     => $direction
        );
        return $this;
    }

    /**
     * Set query limits
     *
     * @param $limit
     * @param null $offset
     * @return $this
     */
    public function setLimit($limit, $offset = null) {
        $this->_limit   = $limit;
        $this->_offset  = $offset;
        return $this;
    }
}