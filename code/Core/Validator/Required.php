<?php
/**
 * Copyright (c) 2018.  MyArtSide
 */

namespace Core\Validator;

/**
 * Class Required
 * @package Core\Validator
 
 */
class Required extends AbstractValidator {

    /**
     * holds message
     *
     * @var string
     */
    protected $_message = 'is required';

    /**
     * Checks if value is set
     *
     * @param $value
     * @return bool
     */
    public function isValid($value){
        return (false === empty($value));
    }
}