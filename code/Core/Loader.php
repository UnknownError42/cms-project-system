<?php
/**
 * Copyright (c) 2018.  MyArtSide 
 */

namespace Core;

/**
 * Class Loader
 * @package Core
 
 */
class Loader {

    /**
     * file extension
     *
     * @var string
     */
    protected $_extension = '.php';

    /**
     * holds directories
     *
     * @var array
     */
    protected $_dirs = array();

    /**
     * Register directory
     *
     * @param $dir
     * @return $this
     */
    public function registerDir($dir) {
        $this->_dirs[] = $dir;
        return $this;
    }

    /**
     * Load requested class
     *
     * @param $class
     * @return bool
     * @throws \Exception
     */
    public function autoload($class) {
        $class = strtr($class, array("\\" => "/"));
        foreach ($this->_dirs as $dir) {
            $file  = $dir . $class . $this->_extension;
            if (is_file($file)) {
                include_once $file;
                return true;
            }
        }
        throw new \Exception("class load error: " . $class);
    }


    /**
     * Register autoload
     *
     * @return bool
     */
    public function register() {
        return spl_autoload_register(array($this, 'autoload'));
    }
}