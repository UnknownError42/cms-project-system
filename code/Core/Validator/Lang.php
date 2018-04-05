<?php
namespace Core\Validator;

/**
 * Class Lang
 * @package Core\Validator
 
 */
class Lang extends AbstractValidator {

    /**
     * holds allowed languages
     *
     * @var array
     */
    protected $_allowed = array();

    /**
     * holds message
     *
     * @var string
     */
    protected $_message = 'is not valid lang';

    /**
     * Lang constructor.
     *
     * @param array $allowed
     */
    public function __construct($allowed = array()) {
        $this->_allowed = $allowed;
    }

    /**
     * Checks if language allowed
     *
     * @param $value
     * @return bool
     */
    public function isValid($value) {
        if (strlen($value) === 2) {
            foreach ($this->_allowed as $key) {
                if ($key === $value) {
                    return true;
                }
            }
        }
        return false;
    }
}