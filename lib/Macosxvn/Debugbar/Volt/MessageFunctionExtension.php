<?php
/**
 * Copyright (c) 2018.  MyArtSide
 */

/**
 * @author macosxvn
 * Date: 04/08/2017
 * Time: 15:22
 */

namespace Macosxvn\Debugbar\Volt;

use Macosxvn\Debugbar\Config;
use Macosxvn\Debugbar\Debugbar;
use Phalcon\DiInterface;
use Phalcon\Mvc\View\Engine\Volt\Compiler;

/**
 * Class MessageFunctionExtension
 *
 * Define Volt function for add message
 *
 * @package Macosxvn\Debugbar\Volt
 */
class MessageFunctionExtension {

    /**
     * @var \Phalcon\DiInterface
     */
    protected $di;

    public function initialize(Compiler $compiler) {
        $di = $this->di;
        $compiler->addFunction("info", function () use ($di) {
        });
    }

    /**
     * @return \Phalcon\DiInterface
     */
    public function getDi() {
        return $this->di;
    }

    /**
     * @param \Phalcon\DiInterface $di
     *
     * @return $this
     */
    public function setDi(DiInterface $di) {
        $this->di = $di;
        return $this;
    }
}