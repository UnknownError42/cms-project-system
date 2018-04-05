<?php
namespace App\Controllers;

use Core\Controller;
use Core\Di;
use Project\AlphaGroup;
use Project\Manager;

class IndexController extends Controller {

    /**
     *
     *
     * @param $projects
     * @return AlphaGroup[]
     */
    protected function getAlphaGroups($projects) {
        $groups = array();
        foreach (range('A', 'Z') as $char) {
            $groups[] = new AlphaGroup($char, $projects);
        }
        return $groups;
    }

    public function indexAction() {
        $user    = Di::getAuth()->getUser();
        $manager = new Manager();
        if ($user->isAdmin()) {
            if (($group = $this->getParam('group', false)) !== false) {
                $projects = $manager->getProjects($group);
                $alphaGroups = $this->getAlphaGroups($projects);

                $this->view->setParam('alphas', $alphaGroups);
                $this->view->setParam('group', $group);
                $this->view->setParam('projects', $projects);
                $this->view->setParam('title', $group);
                $this->view->setParam('dirs', $manager->getGroups($group));
            } else {
                $this->view->setParam('title', 'projects');
            }
            $this->view->setParam('groups', $manager->getGroups());
            $this->view->setFile('project/admin');
        } else {
            $projects = $manager->getProjects($user->name);
            $this->view->setParam('title', 'projects');
            $this->view->setParam('projects', $projects);
            $this->view->setFile('project/user');
        }
        $this->view->setParam('dbUrl', $this->getParam('db', $user->displayDbUrl()));
    }
}