<?php
namespace App;

use Core\Auth;
use Core\Di;

class App {

    public function run() {
        $auth       = new Auth();
        $request    = Di::getRequest();
        $response   = Di::getResponse();
        $dispatcher = Di::getDispatcher();

        $view = Di::getView();
        $view->setPath('../views');

        $layout = $view->getLayout();
        $layout
            ->addCss('/css/bootstrap.valid.min.css')
            ->addCss('/css/font-awesome.min.css')
            ->addCss('/css/morris.css')
            ->addCss('/css/styles.css')

            ->addJs('/js/jquery-1.11.2.min.js')
            ->addJs('/js/bootstrap.min.js')
            ->addJs('/js/raphael.min.js')
            ->addJs('/js/morris.min.js')
            ->addJs('/js/scripts.js')

            ->setTitle('Projects')
        ;

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
}