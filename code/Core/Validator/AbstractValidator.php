<?php
/**
 * Copyright (c) 2018.  MyArtSide 
 */

namespace Core\Validator;

/**
 * Class AbstractValidator
 * @package Core\Validator
 
 */
abstract class AbstractValidator {

    /**
     * Holds message
     *
     * @var string
     */
    protected $_message;

    /**
     * Checks validation
     *
     * @param $value
     * @return bool
     */
    abstract public function isValid($value);

    /**
     * Returns message
     *
     * @return mixed
     */
    public function getMessage() {
        return $this->_message;
    }

    /**
     * Set message
     *
     * @param $message
     * @return $this
     */
    public function setMessage($message) {
        $this->_message = $message;
        return $this;
    }
}