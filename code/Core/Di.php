<?php
namespace Core;

/**
 * Class Reg
 * @package Core
 */
class Di {

    /**
     * holds registered data
     *
     * @var array
     */
    static protected $_data = array();

    /**
     * @return Auth
     */
    static public function getAuth() {
        return self::get('auth', 'Core\\Auth');
    }

    /**
     * @return Session
     */
    static public function getSession() {
        return self::get('session', 'Core\\Session');
    }

    /**
     * @return Dispatcher
     */
    static public function getDispatcher() {
        return self::get('dispatcher', 'Core\\Dispatcher');
    }

    /**
     * @return Request
     */
    static public function getRequest() {
        return self::get('request', 'Core\\Request');
    }

    /**
     * @return Response
     */
    static public function getResponse() {
        return self::get('response', 'Core\\Response');
    }

    /**
     * @return Layout
     */
    static public function getLayout() {
        return self::get('layout', 'Core\\Layout');
    }

    /**
     * @return View
     */
    static public function getView() {
        return self::get('view', 'Core\\View');
    }

    /**
     * @return Security
     */
    static public function getSecurity() {
        return self::get('security', 'Core\\Security');
    }

    /**
     * @return Flash
     */
    static public function getFlash() {
        return self::get('flash', 'Core\\Flash');
    }

    /**
     * @return Config
     */
    static public function getConfig() {
        return self::get('config');
    }

    /**
     * @return Validator
     */
    static public function getValidator() {
        return self::get('validator', 'Core\\Validator');
    }

    /**
     * Returns config key
     *
     * @param $key
     * @param null $default
     * @return null
     */
    static public function get($key, $default = null) {
        if (isset(self::$_data[$key])) {
            return self::$_data[$key];
        }
        if (strpos($default, '\\') !== false) {
            $default = class_exists($default) ? new $default : false;
            if (is_object($default)) {
                self::set($key, $default);
            }
        }
        return $default;
    }

    /**
     * Checks config key
     *
     * @param $key
     * @return bool
     */
    static public function has($key) {
        return isset(self::$_data[$key]);
    }

    /**
     * Set config data
     *
     * @param $key
     * @param $value
     */
    static public function set($key, $value) {
        self::$_data[$key] = $value;
    }
}