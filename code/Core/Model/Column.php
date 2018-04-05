<?php
namespace Core\Model;

class Column {

    protected $_name;
    protected $_type;
    protected $_required = false;
    protected $_primary = false;
    protected $_origin;
    protected $_value;

    public function __construct(\stdClass $data) {
        $this->_name = $data->name;
        $this->_type = $data->type;
        $this->setPrimary($data->pk);
        $this->setRequired($data->notnull);
    }

    protected function setPrimary($pk) {
        $pk = (int)$pk;
        $this->_primary = (bool)$pk;
        return $this;
    }

    protected function setRequired($notnull) {
        $notnull = (int)$notnull;
        $this->_required = (bool)$notnull;
        return $this;
    }

    public function isModified() {
        return ($this->_origin !== $this->_value);
    }

    public function setOrigin($value) {
        $this->_origin = $value;
        return $this;
    }

    public function setValue($value) {
        $this->_value = $value;
        return $this;
    }

    public function getValue() {
        return $this->_value;
    }

    public function getName() {
        return $this->_name;
    }

    public function getType() {
        return $this->_type;
    }

    public function isPrimary() {
        return $this->_primary;
    }

    public function isRequired() {
        return $this->_required;
    }

}