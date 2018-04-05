<?php
namespace Core;

/**
 * Class Dispatcher
 * @package Core
 
 */
class Dispatcher {

    /**
     * holds controller
     *
     * @var
     */
    public $controller;

    /**
     * holds action
     *
     * @var
     */
    public $action;

    /**
     * holds params
     *
     * @var array
     */
    public $params = array();

    /**
     * holds dispatch status
     *
     * @var bool
     */
    public $dispatched = false;

    /**
     *
     *
     * @param $controller
     * @param $action
     * @return bool
     */
    public function prepare($controller, $action) {
        $this
            ->setController($controller)
            ->setAction($action);
        try {
            $class = $this->getControllerClass();
            $this->getActionMethod($class);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Dispatch route
     *
     * @param $controller
     * @param $action
     * @param array $params
     * @return mixed
     */
    public function dispatch($controller, $action, $params = array()) {
        $this->dispatched = false;

        $this
            ->setController($controller)
            ->setAction($action)
            ->setParams($params)
        ;

        $class      = $this->getControllerClass();
        $method     = $this->getActionMethod($class);

        /** @var \Core\Controller $controller */
        $controller = new $class();
        if (method_exists($controller, 'beforeInit')) {
            $controller->beforeInit();
        }
        $controller->initialize();
        if (method_exists($controller, 'afterInit')) {
            $controller->afterInit();
        }
        $result = call_user_func_array(array($controller, $method), array());
        if (method_exists($controller, 'afterDispatch')) {
            $controller->afterDispatch();
        }
        $this->dispatched = true;
        return $result;
    }

    /**
     * Set controller
     *
     * @param $controller
     * @return $this
     */
    public function setController($controller) {
        $this->controller = $controller;
        return $this;
    }

    /**
     * Returns controller class
     *
     * @return string
     */
    public function getControllerClass() {
        $controller = ucfirst($this->controller);
        $controller = 'App\\Controllers\\' . $controller . 'Controller';
        if (false === class_exists($controller)) {
            throw new \InvalidArgumentException(
                "controller " . $controller . " not exists!");
        }
        return $controller;
    }

    /**
     * Set action
     *
     * @param $action
     * @return $this
     */
    public function setAction($action) {
        $this->action = $action;
        return $this;
    }

    /**
     * Returns action method
     *
     * @param Controller $controllerClass
     * @return string
     */
    public function getActionMethod($controllerClass) {
        $actionMethod = $this->action . 'Action';
        if (false === method_exists($controllerClass, $actionMethod)) {
            throw new \InvalidArgumentException(
                "The controller action '{$actionMethod}' has been not defined.");
        }
        return $actionMethod;
    }

    /**
     * Set params
     *
     * @param $params
     * @return $this
     */
    public function setParams($params) {
        $this->params = $params;
        return $this;
    }

    /**
     * Returns param by key
     *
     * @param $key
     * @param null $default
     * @return null|mixed
     */
    public function getParam($key, $default = null) {
        if (isset($this->params[$key])) {
            return $this->params[$key];
        }
        return $default;
    }

    /**
     * Returns all params
     *
     * @return array
     */
    public function getParams() {
        return $this->params;
    }
}