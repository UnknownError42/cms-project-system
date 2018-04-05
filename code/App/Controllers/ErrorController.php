<?php
/**
 * Copyright (c) 2018.  MyArtSide 
 */

namespace App\Controllers;

use Core\Controller;

class ErrorController extends Controller {

    public function viewAction() {
        $this->view->file = 'error/view';
        $this->view->setParam('message', $this->getParam('exception')->getMessage());
    }
}