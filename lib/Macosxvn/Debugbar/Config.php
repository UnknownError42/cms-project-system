<?php
/**
 * Copyright (c) 2018.  MyArtSide
 */

/**
 * @author Nguyen Van Thiep
 * Date: 25/06/2017
 * Time: 17:01
 */

namespace Macosxvn\Debugbar;

class Config {
    // Constants of config variables
    const CONFIG_ENABLED = "enabled";
    const CONFIG_COLLECTORS = "collectors";

    //Define javascript & css vendor
    const CONFIG_VENDOR = "vendor";
    const VENDOR_JQUERY = "jquery";
    const VENDOR_FONTAWESOME = "fontawesome";
    const VENDOR_HIGHLIGHTJS = "highlightjs";

    // constants of collector name
    const COLLECTOR_CONFIG = "phalcon_config";
    const COLLECTOR_DB = "database";
    const COLLECTOR_VIEW = "view";
    const COLLECTOR_SESSION = "session";
    const COLLECTOR_REQUEST = "request";
    const COLLECTOR_LOG = "log";
    const COLLECTOR_MESSAGES = "messages";
    const COLLECTORS = [self::COLLECTOR_CONFIG, self::COLLECTOR_DB, self::COLLECTOR_VIEW, self::COLLECTOR_SESSION, self::COLLECTOR_REQUEST];

    /**
     * @return \Phalcon\Config
     */
    public static function getDefaultConfig() {
        return new \Phalcon\Config([
            self::CONFIG_ENABLED => true,
            // The collectors: db, view, cache, request, session
            self::CONFIG_COLLECTORS => [
                self::COLLECTOR_DB => 'db',
                self::COLLECTOR_VIEW => 'view',
                self::COLLECTOR_SESSION => 'session',
                self::COLLECTOR_REQUEST => 'request',
                self::COLLECTOR_CONFIG => TRUE,
                self::COLLECTOR_MESSAGES => TRUE,
            ],
        ]);
    }
}