<?php
/**
 * Copyright (c) 2018.  MyArtSide 
 */

namespace App\Models;

class StatsRow {

    public $items;
    public $fullname;

    public function __construct($new = false) {
        $this->items = (int)$this->items;
    }

    public function asArray() {
        return array(
            'name' => $this->fullname,
            'items' => $this->items
        );
    }

}