<?php
namespace Core;

use Core\Validator\AbstractValidator;

/**
 * Class Validator
 * @package Core
 
 */
class Validator {

    /**
     * holds validator instances
     *
     * @var array
     */
    protected $_validators = array();

    /**
     *
     *
     * @var Validator
     */
    static protected $_instance = null;

    /**
     * holds default validators
     *
     * @var array
     */
    protected $_defaultValidators = array(
        'int'       => 'Core\Validator\Digit',
        'alpha'     => 'Core\Validator\Alpha',
        'email'     => 'Core\Validator\Email',
        'required'  => 'Core\Validator\Required',
        'slug'      => 'Core\Validator\Slug',
        'path'      => 'Core\Validator\Path',
        'accept'    => 'Core\Validator\Accept',
    );

    /**
     * Validator constructor.
     */
    public function __construct() {
        /**
         * @var string $key
         * @var AbstractValidator $class
         */
        foreach ($this->_defaultValidators as $key => $class) {
            $this->addValidator($key, new $class);
        }
    }

    /**
     *
     *
     * @return Validator
     */
    static public function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new Validator();
        }
        return self::$_instance;
    }

    /**
     * Add validator instance
     *
     * @param $key
     * @param AbstractValidator $class
     * @return $this
     */
    public function addValidator($key, AbstractValidator $class) {
        $this->_validators[$key] = $class;
        return $this;
    }

    /**
     * Returns validator instance
     *
     * @param $key
     * @return AbstractValidator
     * @throws \Exception
     */
    public function get($key) {
        if (false === isset($this->_validators[$key]) || true === empty($this->_validators[$key])) {
            throw new \Exception("Validator not found: " . $key);
        }
        return clone $this->_validators[$key];
    }
}