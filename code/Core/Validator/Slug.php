<?php
/**
 * Copyright (c) 2018.  MyArtSide 
 */

namespace Core\Validator;

/**
 * Class Slug
 * @package Core\Validator
 
 */
class Slug extends AbstractValidator {

    /**
     * holds message
     *
     * @var string
     */
    protected $_message = 'is not valid slug';

    /**
     * Checks if slug is valid
     *
     * @param $value
     * @return bool
     */
    public function isValid($value) {
        $value = htmlspecialchars($value);
        $value = str_replace(array("-","_"), "", $value);

        if (ctype_alnum($value)) {
            return true;
        }

        if (ctype_alpha($value)) {
            return true;
        }

        return false;
    }
}