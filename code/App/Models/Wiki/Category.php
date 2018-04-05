<?php
/**
 * Copyright (c) 2018.  MyArtSide 
 */

namespace App\Models\Wiki;

use Core\Model;

class Category extends Model {

    public $id;
    public $parent_id;
    public $title;

    public $_table = 'wiki_category';

    public function getTitle() {
        return $this->title;
    }

    /**
     *
     *
     * @return Category[]
     */
    public function getRootCategories() {
        return $this->findAllBy('parent_id', 0);
    }

    /**
     *
     *
     * @return Category[]|bool
     */
    public function getChildren() {
        $select = new Model\Query\Select('wiki_category');
        $select->columns('*')
            ->where('parent_id')
            ->order('id ASC');
        $select->query(array($this->id));
        $stmt = $select->execute();
        $children = $stmt->fetchAll(\PDO::FETCH_CLASS, 'App\\Models\\Wiki\\Category', array(false));
        if (count($children) > 0) {
            return $children;
        }
        return false;
    }

    /**
     *
     *
     * @param $id
     * @return bool|Category
     */
    static public function load($id) {
        $model = new Category();
        return $model->findOne($id);
    }

    /**
     *
     *
     * @return Article[]
     */
    public function getArticles() {
        return Article::getArticlesByCategory($this);
    }
}