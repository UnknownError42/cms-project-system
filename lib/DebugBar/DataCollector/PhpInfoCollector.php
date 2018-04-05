<?php
/**
 * Copyright (c) 2018.  MyArtSide
 */

namespace DebugBar\DataCollector;

/**
 * Collects info about PHP
 */
class PhpInfoCollector extends DataCollector implements Renderable
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'php';
    }

    /**
     * @return array
     */
    public function collect()
    {
        return array(
            'version' => PHP_VERSION,
            'interface' => PHP_SAPI
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getWidgets()
    {
        return array(
            "php_version" => array(
                "icon" => "code",
                "tooltip" => "Version",
                "map" => "php.version",
                "default" => ""
            ),
        );
    }
}
