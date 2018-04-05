<?php
/**
 * Copyright (c) 2018.  MyArtSide 
 */

namespace Core;

/**
 * Class Controller
 * @package Core
 
 */
class Controller {

    /**
     * holds view
     *
     * @var View
     */
    public $view;

    /**
     * Initialize controller
     */
    public function initialize() {
        $this->view = Di::getView();
    }

    /**
     * Returns layout instance
     *
     * @return Layout
     */
    public function getLayout() {
        return Di::getLayout();
    }

    /**
     * Returns dispatcher instance
     *
     * @return Dispatcher
     */
    public function getDispatcher() {
        return Di::getDispatcher();
    }

    /**
     * Returns request instance
     *
     * @return Request
     */
    public function getRequest() {
        return Di::getRequest();
    }

    /**
     * Returns response instance
     *
     * @return Response
     */
    public function getResponse() {
        return Di::getResponse();
    }

    /**
     * Returns security instance
     *
     * @return Security
     */
    public function getSecurity() {
        return Di::getSecurity();
    }

    /**
     * Returns flash instance
     *
     * @return Flash
     */
    public function getFlash() {
        return Di::getFlash();
    }

    /**
     * Returns all dispatcher params
     *
     * @return array
     */
    public function getParams() {
        return $this->getDispatcher()->getParams();
    }

    /**
     * Returns dispatcher param
     *
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function getParam($key, $default = null) {
        return $this->getDispatcher()->getParam($key, $default);
    }
}