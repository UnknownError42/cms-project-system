<?php
namespace App\Controllers;

use Core\Controller;
use Core\Di;

class UserController extends Controller {

    public function loginAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
                $user = Di::getAuth()->login($request->post('user'), $request->post('pass'));
                $this->getFlash()->success(sprintf('Herzlich willkommen <strong>%s</strong>', $user->email));
            } catch (\Exception $e) {
                $this->getFlash()->error($e->getMessage());
            }
            $this->getResponse()->redirect('/');
        }
    }

    public function logoutAction() {
        if (($user = Di::getAuth()->logout())) {
            $this->getFlash()->success(sprintf('Auf ein baldiges Wiedersehen <strong>%s</strong>', $user->email));
        }
        $this->getResponse()->redirect('/');
    }
}