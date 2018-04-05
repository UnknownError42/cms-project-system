<?php
/**
 * Copyright (c) 2018.  MyArtSide
 */

include '../data/kint.php';
	#include_once '../data/dBug.php';
	include '../data/functions.php';
	include '../code/Core/Loader.php';
	
	use Core\Loader;
	use Core\Di;


/* stores the real path of this webapp */
//$Paths = getAllPathByEnV();
$debug = false;

$enviroment = getEnvByNetwork();
switch ($enviroment) {
	case 'development':
		//$debugbar = new StandardDebugBar();
		//$debugbarRenderer = $debugbar->getJavascriptRenderer();
		break;
	case 'development-home':
		
		//$debugbar = new StandardDebugBar();
		//$debugbarRenderer = $debugbar->getJavascriptRenderer();
		break;
	case 'testing':
		
		//$debugbar = new StandardDebugBar();
		//$debugbarRenderer = $debugbar->getJavascriptRenderer();
		break;
	case 'production':
		//$debugbar = new StandardDebugBar();
		//$debugbarRenderer = $debugbar->getJavascriptRenderer();

		break;
}

//d($GLOBALS, $enviroment);


$loader  = new Loader();


$loader->registerDir('../code/')->register();
if (isGlobal()){
	$cfg = new \Core\Config(null, '../data/config.php');
	Di::getSession()->start();
	$db  = new \Core\Db($cfg->get('db')->asArray());
	//if($debug){$debugbar["messages"]->addMessage("DB Connection 'default' erhalten");}
	Di::set('db', $db);
}else{
	$cfg = new \Core\Config(null, '../data/config_user.php');
	$docroot = $cfg->get('doc-root');
	Di::getSession()->start();
	$db  = new \Core\Db($cfg->get('db')->asArray());
	//if($debug){$debugbar["messages"]->addMessage("DB Connection 'default' erhalten");}
	Di::set('db', $db);
	$users_db  = new \Core\Db($cfg->get('users_db')->asArray());
	Di::set('users_db', $users_db);
	//if($debug){$debugbar["messages"]->addMessage("DB Connection 'Users_DB' erhalten");}
	$projects_db  = new \Core\Db($cfg->get('projects_db')->asArray());
	//if($debug){$debugbar["messages"]->addMessage("DB Connection 'Projects_DB' erhalten");}
	Di::set('projects_db', $projects_db);
}


Di::set('config', $cfg);

$app = new \App\App();

//if($debug){$debugbar["messages"]->addMessage("App erzeugt");}
/*
 * $app = new \Phalcon\Mvc\Application();
 *
 * 	DI::set('app', $app);
 * 	Di::set(\Macosxvn\Debugbar\Debugbar::SERVICE_NAME, new \Macosxvn\Debugbar\Debugbar($di));
 *
 */

$app->run();
//if($debug){$debugbar["messages"]->addMessage("App gestartet");}
