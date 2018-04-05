<?php
/**
 * Copyright (c) 2018.  MyArtSide
 */

namespace App\Controllers;

use App\Models\Wiki\Article;
use App\Models\Wiki\Category;
use Core\Controller;

class WikiController extends Controller {

    /**
     * @param $rows Category[]
     * @param int $level
     * @param int $active
     * @return string
     */
    public function fetchTree($rows, $level = 0, $active = 0) {
        $html = '<ul>';
        foreach ($rows as $item) {
            $isActive = ($active === $item->id);
            $href = http_build_query(array(
                'controller' => 'wiki',
                'action' => 'index',
                'category' => $item->id
            ));
            $name = $item->getTitle();
            if ($isActive) $name = '<strong>'.$name.'</strong>';
            $html .= '<li>';
            $html .= '<a href="?'.$href.'">'.$name.'</a>';
            if (false !== ($children = $item->getChildren())) {
                $html .= $this->fetchTree($children, ($level + 1), $active);
            }
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    public function getTree() {
        $model = new Category();
        $rootRows = $model->getRootCategories();
        return $this->fetchTree($rootRows, 0, $this->getParam('category', 0));
    }

    /**
     * @return bool|Category
     */
    public function getCategory() {
        $model = new Category();
        return $model->findOne($this->getParam('category', 0));
    }

    public function indexAction() {
        if (($category = $this->getCategory())) {
            $this->view->setParam('category', $category);
            $this->view->setParam('articles', $category->getArticles());
        }
        $this->view->setParam('tree', $this->getTree());
    }

    public function articleAction() {
        $article = Article::load($this->getParam('article'));
        if ($article && $article->category_id === $this->getParam('category')) {
            $this->view->setParam('article', $article);
            $this->view->setParam('category', $article->getCategory());
        }
        $this->view->setParam('tree', $this->getTree());
    }
}