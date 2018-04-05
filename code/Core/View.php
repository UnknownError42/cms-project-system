<?php
/**
 * Copyright (c) 2018.  MyArtSide
 */

namespace Core;

/**
 * Class View
 * @package Core
 
 */
class View {

    /**
     * holds view params
     *
     * @var array
     */
    public $params = array();

    /**
     * holds view file
     *
     * @var string
     */
    public $file;

    /**
     * holds view path
     *
     * @var string
     */
    public $path;

    /**
     * holds file extensions
     *
     * @var string
     */
    public $extension = '.phtml';

    /**
     * holds file of content
     *
     * @var string
     */
    protected $_content;

    /**
     * holds content before
     *
     * @var string
     */
    protected $_beforeContent;

    /**
     * holds content after
     *
     * @var string
     */
    protected $_afterContent;

    /**
     * holds content
     *
     * @var string
     */
    public $content;

    public $layout = 'default';

    /**
     * Prepend content html
     *
     * @param $html
     * @return $this
     */
    public function beforeContent($html) {
        $this->_beforeContent .= $html;
        return $this;
    }

    /**
     * Append content html
     *
     * @param $html
     * @return $this
     */
    public function afterContent($html) {
        $this->_afterContent .= $html;
        return $this;
    }

    /**
     * Set view path
     *
     * @param $path
     * @return $this
     */
    public function setPath($path) {
        $this->path = $path;
        return $this;
    }

    /**
     * Set view file
     *
     * @param $file
     * @return $this
     */
    public function setFile($file) {
        $this->file = $file;
        return $this;
    }

    /**
     * Has view file
     *
     * @return bool
     */
    public function hasFile() {
        return ($this->file !== null);
    }

    /**
     * Returns view file
     *
     * @return string
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * Set layout file
     *
     * @param $layout
     * @return $this
     */
    public function setLayout($layout) {
        $this->layout = $layout;
        return $this;
    }

    /**
     * Set view param
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function setParam($key, $value) {
        $this->params[$key] = $value;
        return $this;
    }

    /**
     * Returns view param
     *
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function getParam($key, $default = null) {
        if (isset($this->params[$key])) {
            return $this->params[$key];
        }
        return $default;
    }

    /**
     *
     *
     * @return array
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * Returns view file path
     *
     * @param $file
     * @return string
     */
    public function getFilePath($file) {
        return $this->path . '/' . $file . $this->extension;
    }

    /**
     * Set content
     *
     * @param $content
     * @return $this
     */
    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    /**
     * Returns content
     *
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getLayoutName() {
        return $this->layout;
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
     * Returns page header
     *
     * @return string
     */
    public function getHeader() {
        return $this->getLayout()->head();
    }

    /**
     * Returns page footer
     *
     * @return string
     */
    public function getFooter() {
        return $this->getLayout()->foot();
    }

    /**
     * Render partial view
     *
     * @param $file
     * @param array $params
     * @return mixed|string
     */
    public function partial($file, $params = array()) {
        $file = $this->getFilePath($file);
        extract($params);
        ob_start();
        include $file;
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    /**
     * Returns rendered view
     *
     * @param $file
     * @param array $params
     * @return string
     */
    public function render($file = null, $params = array()) {
        $file = $this->path . '/' . ($file === null ? $this->file : $file) . $this->extension;
        extract($params);
        ob_start();
        include_once $file;
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}