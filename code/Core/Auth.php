<?php
namespace Core;

use App\Models\User;

class Auth {

    public function login($name, $pass) {
        $message = "Ungültige Benutzerdaten";
        if (empty($name) || empty($pass) || strlen($name) < 4 || strlen($pass) < 4) {
            throw new \Exception($message);
        }
        $user = User::getUserByName($name);
        if (false === $user) {
            throw new \Exception($message);
        }
        $security = Di::getSecurity();
        if (false === $security->checkHash($pass, $user->password)) {
            throw new \Exception($message);
        }
        $cookieHash = $security->hash($user->name . $user->password);
        $cookieLife = ((3600 * 24) * 30) * 12;
        setcookie('user', $cookieHash, (time() + $cookieLife));
        Di::getSession()->set('auth', $user);
        return $user;
    }

    public function logout() {
        if (($user = $this->getUser())) {
            Di::getSession()->remove('auth');
            setcookie('user', null, (time()));
            return $user;
        }
        return false;
    }

    /**
     *
     *
     * @return bool
     */
    public function isLoggedIn() {
        $loggedIn = ($this->getUser() instanceof User);
        if (false === $loggedIn) {
            $autoUser = $this->getAutoUser();
            return ($autoUser && $autoUser instanceof User);
        }
        return true;
    }

    /**
     *
     *
     * @return User
     */
    public function getUser() {
        return Di::getSession()->get('auth');
    }

    /**
     *
     *
     * @return User
     * @throws \Exception
     */
    public function getAutoUser() {
        if (isset($_COOKIE['user'])) {
            $model      = new User();
            $security   = Di::getSecurity();
            /** @var User $user */
            foreach ($model->findAll() as $user) {
                if ($security->checkHash($user->name . $user->password, $_COOKIE['user'])) {
                    Di::getSession()->set('auth', $user);
                    return $user;
                }
            }
        }
        return false;
    }
}