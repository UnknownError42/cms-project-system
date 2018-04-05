<?php
/**
 * Copyright (c) 2018.  MyArtSide 
 */

namespace App\Controllers;

use App\Models\Stats;
use Core\Di;
use Core\Controller;
use Project\Manager;

class ProjectController extends Controller {

    protected function getUser() {
        return Di::getAuth()->getUser();
    }

    protected function getProjectName() {
        $name = $this->getRequest()->post('name', false);
        if (false === $name || strlen($name) < 6) {
            throw new \Exception(sprintf("Ungültiger Projektname: %s", $name));
        }
        return $name;
    }

    protected function getProjectCore() {
        return $this->getRequest()->post('core', 'cms');
    }

    public function viewAction() {
        $this->view->setParam('title', $this->getParam('id'));
    }

    public function statsAction() {
        $model = new Stats();
        $this->view->setParam('stats', $model->getRanking());
    }

    public function createAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $manager = new Manager();
            try {
                $userDir = $this->getUser()->name;
                $coreKey = $this->getProjectCore();
                $project = $this->getProjectName();
                $manager->createProject($userDir, $coreKey, $project);
                $this->getFlash()->success(sprintf("Projekt <strong>%s</strong> erfolgreich erstellt.", $project));
            } catch (\Exception $e) {
                $this->getFlash()->error($e->getMessage());
            }
        }
        $this->getResponse()->redirect('/');
    }

    public function moveAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $manager = new Manager();
            try {
                if (false === $this->getUser()->isAdmin()) {
                    throw  new \Exception("Keine Berechtigungen um Projekte zu verschieben");
                }
                $name = $request->post('name');
                $path = $request->post('to');
                $manager->moveProject($name, $path);
                $this->getFlash()->success(sprintf("Projekt <strong>%s</strong> wurde nach <strong>%s</strong> verschoben", $name, $path));
            } catch (\Exception $e) {
                $this->getFlash()->error($e->getMessage());
            }
        }
        $this->getResponse()->redirect('/');
    }
}