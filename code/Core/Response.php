<?php
/**
 * Copyright (c) 2018.  MyArtSide 
 */

namespace Core;

/**
 * Class Response
 * @package Core
 
 */
class Response {

    /**
     * holds headers
     *
     * @var array
     */
    public $headers = array();

    /**
     * holds content type
     *
     * @var string
     */
    public $type = 'text/html';

    /**
     * holds response encoding
     *
     * @var string
     */
    public $encoding = 'UTF-8';

    /**
     * holds language
     *
     * @var string
     */
    public $lang;

    /**
     * holds content
     *
     * @var
     */
    public $content;

    /**
     * holds expire
     *
     * @var bool
     */
    public $expire = false;

    /**
     * Add header
     *
     * @param $name
     * @param null $value
     * @param null $replace
     * @param null $code
     * @return $this
     */
    public function addHeader($name, $value = null, $replace = null, $code = null) {
        $this->headers[] = array(
            'name'      => $name,
            'value'     => $value,
            'replace'   => $replace,
            'code'      => $code
        );
        return $this;
    }

    /**
     * Set cached headers
     *
     * @param $eTag
     * @param $lastModified
     * @return $this
     */
    public function setCachedContent($eTag, $lastModified) {
        $this
            ->addHeader('Cache-Control', 'public', true)
            ->addHeader('Pragma', 'public', true)
            ->addHeader('ETag', $eTag)
            ->setLastModified($lastModified);
        return $this;
    }

    /**
     * Send json data
     *
     * @param array $data
     * @param bool $exit
     */
    public function sendJson($data = array(), $exit = false) {
        $this->type = 'application/json';
        $this->setContent(json_encode($data));
        $this->send($exit, false);
    }

    /**
     * Set language
     *
     * @param $lang
     * @return $this
     */
    public function setLang($lang) {
        $this->lang = $lang;
        return $this;
    }

    /**
     * Set status
     *
     * @param $status
     * @param $code
     * @return $this
     */
    public function setStatus($status, $code) {
        $string = 'HTTP/1.1 ' . $code . ' ' . $status;
        $this->addHeader($string, null, true, $code);
        return $this;
    }

    /**
     * Returns headers
     *
     * @return array
     */
    public function getHeaders() {
        return $this->headers;
    }

    /**
     * Checks if header exists
     *
     * @param $name
     * @return bool
     */
    public function has($name) {
        foreach ($this->headers as $header) {
            if (true === isset($header[$name])) {
                return true;
            }
        }
        return false;
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
     * @return mixed
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Redirect to custom uri
     *
     * @param $uri
     * @param int $code
     */
    public function redirect($uri, $code = 301) {
        header('Location: ' . $uri);
        exit;
    }

    /**
     * Set expire time
     *
     * @param $time
     * @return $this
     */
    public function setExpire($time) {
        $date = gmdate("D, d M Y H:i:s", $time) . ' GMT';
        $this->addHeader('Expires', $date, true);
        $this->expire = true;
        return $this;
    }

    /**
     * Set last modified response
     *
     * @param $time
     * @param int $code
     * @return $this
     */
    public function setLastModified($time, $code = 200) {
        $date = gmdate("r", $time);
        $this->addHeader('Last-Modified', $date, true, $code);
        return $this;
    }

    /**
     * Set not modified response
     *
     * @param int $code
     * @return $this
     */
    public function notModified($code = 304) {
        $this->setStatus('Not Modified', $code);
        return $this;
    }

    /**
     * Prepare response content
     *
     * @return mixed|string
     */
    protected function preparedContent() {
        $content = $this->getContent();
        $content = html_entity_decode($content, null, $this->encoding);
        return $content;
    }

    /**
     * Send response
     *
     * @param bool $exit
     * @param bool $prepare
     */
    public function send($exit = false, $prepare = true) {
        if (false === headers_sent()) {
            if (false === $this->expire) {
                header_remove('Expires');
            }
            /** @TODO: Content-Language: de */
            $this->addHeader('Content-Type', $this->type . '; charset=' . $this->encoding);
            foreach($this->headers as $h) {
                $string = $h['name'] . (($h['value'] !== null) ? ': ' . $h['value'] : '');
                header($string, $h['replace'] , $h['code'] );
            }
            if ($this->content !== null) {
                if ($prepare === true) {
                    echo $this->preparedContent();
                } else {
                    echo $this->getContent();
                }
            }
        }
        if ($exit) {
            exit;
        }
    }
}