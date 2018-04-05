<?php
/**
 * Copyright (c) 2018.  MyArtSide 
 */

namespace Core;

/**
 * Class Session
 * @package Core
 
 */
class Session {

    /**
     * holds current namespace
     *
     * @var string
     */
    protected $_namespace;

    /**
     * holds status
     *
     * @var bool
     */
    protected $_started = false;

    /**
     *
     *
     * @var Session
     */
    static protected $_instance = null;

    /**
     * Session constructor.
     *
     * @param string $namespace
     */
    public function __construct($namespace = 'default') {
        $this->_namespace = $namespace;
        $this->start();
    }

    /**
     *
     *
     * @param string $namespace
     * @return Session
     */
    static public function getInstance($namespace = 'default') {
        if (self::$_instance === null) {
            self::$_instance = new Session($namespace);
        }
        return self::$_instance;
    }

    /**
     * Start session
     *
     * @return $this
     */
    public function start() {
        if ($this->isStarted() === false) {
            if (false === headers_sent()) {
                session_start();
            }
            $this->_started = true;
        }
        return $this;
    }

    /**
     * Returns status
     *
     * @return bool
     */
    public function isStarted() {
        return $this->_started;
    }

    /**
     * Regenerates session id
     *
     * @return bool
     */
    public function regenerate() {
        return ($this->isStarted() && session_regenerate_id(true));
    }

    /**
     * Returns session id
     *
     * @return string
     */
    public function getId() {
        return session_id();
    }

    /**
     * Checks if data exists
     *
     * @param $key
     * @return bool
     */
    public function has($key) {
        return isset($_SESSION[$key]);
    }

    /**
     * Returns data
     *
     * @param $key
     * @return mixed
     */
    public function get($key) {
        if ($this->has($key)) {
            return $_SESSION[$key];
        }
        return false;
    }

    /**
     * Set data
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function set($key, $value) {
        $_SESSION[$key] = $value;
        return $this;
    }

    /**
     * Remove data
     *
     * @param $key
     * @return $this
     */
    public function remove($key) {
        if ($this->has($key)) {
            unset ($_SESSION[$key]);
        }
        return $this;
    }

    /**
     * Destroy current session
     *
     * @return bool
     */
    public function destroy() {
        if (false === $this->isStarted()) {
            return true;
        }
        return session_destroy();
    }
}