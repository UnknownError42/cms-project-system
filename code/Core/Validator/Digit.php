<?php
/**
 * Copyright (c) 2018.  MyArtSide 
 */

namespace Core\Validator;

/**
 * Class Digit
 * @package Core\Validator
 
 */
class Digit extends AbstractValidator {

    /**
     * holds message
     *
     * @var string
     */
    protected $_message = 'is not digit';

    /**
     * Checks if value is digit
     *
     * @param $value
     * @return bool
     */
    public function isValid($value){
        return ctype_digit($value);
    }
}