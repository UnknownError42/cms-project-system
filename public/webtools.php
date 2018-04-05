<?php

/**
 * Copyright (c) 2018.  MyArtSide
 */

use Phalcon\Bootstrap;

include 'webtools.config.php';
include PTOOLSPATH . '/bootstrap/autoload.php';

$bootstrap = new Bootstrap([
    'ptools_path' => PTOOLSPATH,
    'ptools_ip'   => PTOOLS_IP,
    'base_path'   => BASE_PATH,
]);

if (APPLICATION_ENV === ENV_TESTING) {
    return $bootstrap->run();
} else {
    echo $bootstrap->run();
}
