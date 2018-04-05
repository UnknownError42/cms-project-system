<?php

include '../code/Core/Loader.php';

use Core\Loader;
use Core\Di;

$loader  = new Loader();
$loader->registerDir('../code/')->register();

$cfg = new \Core\Config(null, '../data/config.php');
$db  = new \Core\Db($cfg->get('db')->asArray());

Di::getSession()->start();
Di::set('db', $db);
Di::set('config', $cfg);

$app = new \App\App();
$app->run();
