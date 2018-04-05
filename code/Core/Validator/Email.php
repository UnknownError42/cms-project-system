<?php
/**
 * Copyright (c) 2018.  MyArtSide
 */

namespace Core\Validator;

/**
 * Class Email
 * @package Core\Validator
 
 */
class Email extends AbstractValidator {

    /**
     * holds message
     *
     * @var string
     */
    protected $_message = 'is not valid email';

    /**
     * Checks if email is valid
     *
     * @param $value
     * @return mixed
     */
    public function isValid($value){
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}