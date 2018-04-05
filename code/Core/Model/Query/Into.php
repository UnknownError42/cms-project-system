<?php
namespace Core\Model\Query;

class Into {

    protected $_table;
    protected $_data = array();

    public function __construct($table) {
        $this->_table = $table;
    }

    public function set($key, $value = '?') {
        $this->_data[$key] = $value;
        return $this;
    }

    public function build() {
        $query      = array('INSERT INTO');
        $query[]    = $this->_table . '('.implode(', ', array_keys($this->_data)).')';
        $query[]    = 'VALUES('.implode(',', array_values($this->_data)).')';
        return implode(" ", $query);
    }
}