<?php
/**
 * Copyright (c) 2018.  MyArtSide 
 */

namespace Core;

/**
 * Class Config
 * @package Core
 
 */
class Config {

    /**
     * holds data
     *
     * @var array
     */
    protected $_data = array();

    /**
     * Create config from array
     *
     * Config constructor
     * @param array $data
     * @param null $file
     */
    public function __construct($data = array(), $file = null) {
        if ($file !== null) {
            if (is_file($file)) {
                $data = include $file;
            }
        }
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = new Config($value);
            }
            $this->_data[$key] = $value;
        }
    }

    /**
     * Clear data
     *
     * @return $this
     */
    public function clear() {
        $this->_data = array();
        return $this;
    }

    /**
     * Returns config data
     *
     * @param $key
     * @param null $default
     * @return Config|mixed|null
     */
    public function get($key, $default = null) {
        if ($this->has($key)) {
            return $this->_data[$key];
        }
        return $default;
    }

    /**
     * Add data
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function add($key, $value) {
        $this->_data[$key] = $value;
        return $this;
    }

    /**
     * Check key
     *
     * @param $key
     * @return bool
     */
    public function has($key) {
        return isset($this->_data[$key]);
    }

    /**
     * Returns all data
     *
     * @return array
     */
    public function getData() {
        return $this->_data;
    }

    /**
     * Returns as array
     *
     * @return array
     */
    public function asArray() {
        $data = array();
        foreach ($this->_data as $key => $value) {
            if ($value instanceof Config) {
                $value = $value->asArray();
            }
            $data[$key] = $value;
        }
        return $data;
    }
}