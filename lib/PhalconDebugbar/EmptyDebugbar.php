<?php
/**
 * Copyright (c) 2018.  MyArtSide 
 */

/**
 * User: zhuyajie
 * Date: 15-6-19
 * Time: 下午10:40
 */

namespace Snowair\Debugbar;


class EmptyDebugbar {
    public function __call($method,$params)
    {

    }

    static public function __callStatic($method,$params)
    {

    }

}