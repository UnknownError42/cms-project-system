<?php
namespace Core\Validator;

/**
 * Class SecurePassword
 * @package Core\Validator
 
 */
class SecurePassword extends AbstractValidator {

    /**
     * holds message
     *
     * @var string
     */
    protected $_message = 'is not secure';

    /**
     * Checks if value is secure
     *
     * @param $value
     * @return bool
     */
    public function isValid($value) {
        if (strlen($value) < 8) {
            return false;
        }
        if (ctype_alnum($value) || ctype_digit($value) || ctype_alpha($value)) {
            return false;
        }
        return true;
    }
}