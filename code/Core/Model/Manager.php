<?php
/**
 * Copyright (c) 2018.  MyArtSide 
 */

namespace Core\Model;

use Core\Model;

class Manager {

    public $model;
    public $columns = null;

    public function __construct(Model $model) {
        $this->model = $model;
        $query = sprintf("PRAGMA table_info(%s)", $model->_table);
        $stmt = $model->getPDO()->prepare($query);
        $stmt->execute();
        $this->columns = array();
        foreach ($stmt->fetchAll() as $row) {
            $this->columns[$row->name] = new Column($row);
        }
    }

    /**
     *
     *
     * @param $name
     * @return Column
     */
    public function getColumn($name) {
        return $this->columns[$name];
    }

    /**
     *
     *
     * @param $name
     * @return bool
     */
    public function hasColumn($name) {
        return isset($this->columns[$name]);
    }
}