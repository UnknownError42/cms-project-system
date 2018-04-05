<?php
/**
 * Copyright (c) 2018.  MyArtSide 
 */

namespace Core\Validator;

/**
 * Class Accept
 * @package Core\Validator
 
 */
class Accept extends AbstractValidator {

    /**
     * holds message
     *
     * @var string
     */
    protected $_message = 'not accepted';

    /**
     * Checks if value is one
     *
     * @param $value
     * @return bool
     */
    public function isValid($value) {
        return ($value === '1');
    }
}