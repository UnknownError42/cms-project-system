<?php
/**
 * Copyright (c) 2018.  MyArtSide 
 */

namespace App;

use Core\Auth;
use Core\Di;
//use Debugbar\StandardDebugBar;
use function explode;
use function getAllPathByEnV;
use function getEnvByNetwork;
use function isGlobal;
use function mb_stripos;
use function stristr;

class App {
	
	public $_debug = false;

    public function run() {
	    
        $auth       = new Auth();
        $request    = Di::getRequest();
        $response   = Di::getResponse();
        $dispatcher = Di::getDispatcher();

        $view = Di::getView();
        $view->setPath('../views');

        $layout = $view->getLayout();
        /*
        $layout
            ->addCss('/css/bootstrap.valid.min.css')
            ->addCss('/css/font-awesome.min.css')
            ->addCss('/css/morris.css')
            //->addCss('/css/sidr.dark.css')
            ->addCss('/css/jquery-ui/jquery-ui.min.css')
            ->addCss('/css/styles.css')

            ->addJs('/js/jquery-3.3.1.min.js')
            ->addJs('/js/bootstrap-3.3.2.min.js')
            ->addJs('/js/jquery-ui-1.12.1.min.js')
            ->addJs('/js/jquery.sidr.min.js')
            /*
            ->addJs('/js/require-2.3.5.min.js')
            ->addJs('/js/elfinder.main.js')
            ->addJs('/js/elfinder/extras/editors.default.min.js')
            *//*
            ->addJs('/js/raphael.min.js')
            ->addJs('/js/morris.min.js')
            ->addJs('/js/scripts.js')

            ->setTitle('Projects')
        ;*/
	    if(isGlobal()){
	    $layout
		    ->addCss('/css/bwtheme/bootstrap.min.css')
		    ->addCss('/css/bwtheme/font-awesome.min.css')
		    ->addCss('/css/bwtheme/bootstrap-checkbox.css')
		    ->addCss('/css/bwtheme/bootstrap-select.min.css')
		    ->addCss('/css/bwtheme/circleprogress.css')
		    ->addCss('/css/bwtheme/login.css')
		    ->addCss('/css/bwtheme/bwtheme.css')
		    ->addCss('/css/morris.css')
		    ->addCss('/css/sidr.dark.css')
		    ->addCss('/css/jquery-ui/jquery-ui.min.css')
		    ->addCss('/css/styles.css')
		    
		    ->addJs('/js/bwtheme/jquery.min.js')
		    ->addJs('/js/jquery-ui-1.12.1.min.js')
		    ->addJs('/js/jquery.sidr.min.js')
		    ->addJs('/js/bwtheme/bootstrap.min.js')
		    ->addJs('/js/bwtheme/bootstrap-filestyle.js')
		    ->addJs('/js/bwtheme/bootstrap-select.min.js')
		    ->addJs('/js/bwtheme/bwtheme.js')
		    /*
			->addJs('/js/elfinder.main.js')
			->addJs('/js/elfinder/extras/editors.default.min.js')
			*/
		    ->addJs('/js/require-2.3.5.min.js')
            ->addJs('/js/raphael.min.js')
            ->addJs('/js/morris.min.js')
            ->addJs('/js/scripts.js')
            ->setTitle('UDEV Projekte');
		
	    }else{
	    	$pArray = explode('/', $_SERVER['PHP_SELF']);
		    if(($path = stristr($pArray[1], '~')) !==false){
			    $usr_assets = '/'.$path.'/public';
		    }else{$usr_assets = '';}
		    
		    
		    $layout
			    ->addCss($usr_assets.'/css/bwtheme/bootstrap.min.css')
			    ->addCss($usr_assets.'/css/bwtheme/font-awesome.min.css')
			    ->addCss($usr_assets.'/css/bwtheme/bootstrap-checkbox.css')
			    ->addCss($usr_assets.'/css/bwtheme/bootstrap-select.min.css')
			    ->addCss($usr_assets.'/css/bwtheme/circleprogress.css')
			    ->addCss($usr_assets.'/css/bwtheme/login.css')
			    ->addCss($usr_assets.'/css/bwtheme/bwtheme.css')
			    ->addCss($usr_assets.'/css/morris.css')
			    ->addCss($usr_assets.'/css/sidr.dark.css')
			    ->addCss($usr_assets.'/css/jquery-ui/jquery-ui.min.css')
			    ->addCss($usr_assets.'/css/styles.css')
			
			    ->addJs($usr_assets.'/js/bwtheme/jquery.min.js')
			    ->addJs($usr_assets.'/js/jquery-ui-1.12.1.min.js')
			    ->addJs($usr_assets.'/js/jquery.sidr.min.js')
			    ->addJs($usr_assets.'/js/bwtheme/bootstrap.min.js')
			    ->addJs($usr_assets.'/js/bwtheme/bootstrap-filestyle.js')
			    ->addJs($usr_assets.'/js/bwtheme/bootstrap-select.min.js')
			    ->addJs($usr_assets.'/js/bwtheme/bwtheme.js')
			    ->addJs($usr_assets.'/js/require-2.3.5.min.js')
			    ->addJs($usr_assets.'/js/raphael.min.js')
			    ->addJs($usr_assets.'/js/morris.min.js')
			    ->addJs($usr_assets.'/js/scripts.js')
			    ->setTitle('Homedir Projekte');
	    }

        $controller = $request->getController();
        $action     = $request->getAction();
        $params     = $request->getParams();

        if (false === $auth->isLoggedIn()) {
            $controller = 'user';
            $action     = 'login';
        }
        if (false === $dispatcher->prepare($controller, $action)) {
            $controller = 'error';
            $action     = 'view';
            $params     = array(
                'exception' => new \Exception("route can not dispatch")
            );
        }

        $dispatcher->dispatch($controller, $action, $params);

        if (false === $view->hasFile()) {
            $view->setFile($dispatcher->controller . '/' . $dispatcher->action);
        }

        if (($user = $auth->getUser())) {
            $view->setParam('user', $user);
        }

        $content = $view->partial($view->getFile(), $view->getParams());
        $layout  = $view->getLayoutName();
        $view->setContent($content);

        $response
            ->setContent($view->render($layout, $view->getParams()))
            ->send(true);
    }
    
    public function getDebug(){
    	if($this->_debug === DEBUG){
		    return $this->_debug;
	    }else{
		    $this->_debug = DEBUG;
		    return $this->_debug;
	    }
    }
	
	public function setDebug($debug = false){
        if($debug !== DEBUG){
        	define('DEBUG', $debug);
	        $this->_debug = DEBUG;
        }
		return $this->_debug;
	}
}