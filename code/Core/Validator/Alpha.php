<?php
/**
 * Copyright (c) 2018.  MyArtSide 
 */

namespace Core\Validator;

/**
 * Class Alpha
 * @package Core\Validator
 
 */
class Alpha extends AbstractValidator {

    /**
     * holds message
     *
     * @var string
     */
    protected $_message = 'is not alpha';


    /**
     * Checks if value contains only alphabetic chars
     *
     * @param $value
     * @return bool
     */
    public function isValid($value){
        return ctype_alpha($value);
    }
}