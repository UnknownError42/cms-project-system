<?php
/**
 * Copyright (c) 2018.  MyArtSide 
 */

namespace Core;

/**
 * Class Request
 * @package Core
 
 */
class Request {

    /**
     * holds get method
     */
    const METHOD_GET  = 'GET';

    /**
     * holds post method
     */
    const METHOD_POST = 'POST';

    /**
     * holds requested uri
     *
     * @var string
     */
    protected $_uri;

    /**
     * holds requested method
     *
     * @var string
     */
    protected $_method;

    /**
     * Request constructor.
     */
    public function __construct() {
        $this->_uri    = $_SERVER['REQUEST_URI'];
        $this->_method = $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Returns server host
     *
     * @return mixed
     */
    public function getHost() {
        return $_SERVER['HTTP_HOST'];
    }

    /**
     * Returns requested uri
     *
     * @return mixed
     */
    public function getUri() {
        return $this->_uri;
    }

    /**
     * Returns hashed uri
     *
     * @return string
     */
    public function getHash() {
        return 'page uri=' . $this->getUri();
    }

    /**
     * Check if headers is modified
     *
     * @return bool|int
     */
    public function isModified() {
        if (false === empty($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
            return strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
        }
        return false;
    }

    /**
     * Returns request method
     *
     * @return string
     */
    public function getMethod() {
        return $this->_method;
    }

    /**
     * Returns requested controller
     *
     * @return mixed|null
     */
    public function getController() {
        return $this->get('controller', 'index', 'slug');
    }

    /**
     * Returns requested action
     *
     * @return mixed|null
     */
    public function getAction() {
        return $this->get('action', 'index', 'alpha');
    }

    /**
     * Returns url params
     *
     * @return array
     */
    public function getParams() {
        $params = $this->get();
        foreach ($params as $key => $value) {
            if (in_array($key, array('controller', 'action'))) {
                unset($params[$key]);
            }
        }
        return $params;
    }

    /**
     * Check ajax request
     *
     * @return bool
     */
    public function isAjax() {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            return (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
        }
        return false;
    }

    /**
     * Checks post request
     *
     * @return bool
     */
    public function isPost() {
        return ($this->getMethod() === self::METHOD_POST);
    }

    /**
     * Returns url-param data
     *
     * @param null $param
     * @param null $default
     * @param null $validator
     * @param null $exclude
     * @return mixed|null
     * @throws \Exception
     */
    public function get($param = null, $default = null, $validator = null, $exclude = null) {
        return $this->_fetchData(
            $_GET, $param, $default, $validator, $exclude
        );
    }

    /**
     * Returns post data
     *
     * @param null $param
     * @param null $default
     * @param null $validator
     * @param null $exclude
     * @return mixed|null
     * @throws \Exception
     */
    public function post($param = null, $default = null, $validator = null, $exclude = null) {
        return $this->_fetchData(
            (isset($_POST) ? $_POST : array()), $param, $default, $validator, $exclude
        );
    }

    /**
     * Returns custom data
     *
     * @param $data
     * @param null $param
     * @param null $default
     * @param null $validatorKey
     * @param null $exclude
     * @return mixed|null
     * @throws \Exception
     */
    protected function _fetchData($data, $param = null, $default = null, $validatorKey = null, $exclude = null) {
        if ($exclude !== null) {
            if (is_array($exclude)) {
                foreach ($exclude as $param) {
                    if (isset($data[$param])) {
                        unset ($data[$param]);
                    }
                }
            } else {
                if (isset($data[$exclude])) {
                    unset ($data[$exclude]);
                }
            }
        }

        if ($param === null) {
            return $data;
        }

        if (isset($data[$param]) && !empty($data[$param])) {
            if ($validatorKey !== null) {
                if (is_array($validatorKey)) {
                    foreach ($validatorKey as $name) {
                        $validator = $this->getValidator()->get($name);
                        if ($validator->isValid($data[$param]) === false) {
                            throw new \Exception("invalid data param: " . $param);
                        }
                    }
                    return $data[$param];
                }
                $validator = $this->getValidator()->get($validatorKey);
                if ($validator->isValid($data[$param]) === false) {
                    throw new \Exception("invalid data param: " . $param);
                }
                return $data[$param];
            }
            return $data[$param];
        }
        return $default;
    }

    /**
     * Returns validator
     *
     * @return Validator
     */
    public function getValidator() {
        return Di::getValidator();
    }

}