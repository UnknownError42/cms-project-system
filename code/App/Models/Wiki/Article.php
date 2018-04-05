<?php
/**
 * Copyright (c) 2018.  MyArtSide
 */

namespace App\Models\Wiki;

use Core\Model;

class Article extends Model {

    public $id;
    public $category_id;
    public $user_id;
    public $created_at;
    public $updated_at;
    public $title;
    public $content;

    public $_author;

    public $_table = 'wiki_article';

    public function getTitle() {
        return $this->title;
    }

    public function getAuthor() {
        return $this->_author;
    }

    public function getContent() {
        return $this->content;
    }

    public function getCreatedDate() {
        return date("d.m.Y", $this->created_at);
    }

    public function getUpdateDate() {
        return date("d.m.Y", $this->updated_at);
    }

    public function getUrl() {
        $href = http_build_query(array(
            'controller' => 'wiki',
            'action' => 'article',
            'category' => $this->category_id,
            'article'  => $this->id
        ));
        return '?' . $href;
    }

    /**
     *
     *
     * @return Category|bool
     */
    public function getCategory() {
        return Category::load($this->category_id);
    }

    /**
     *
     *
     * @param Category $category
     * @return Article[]
     */
    static public function getArticlesByCategory(Category $category) {
        $select = new Model\Query\Select('wiki_article', 'a');
        $select->columns('a.*, u.fullname AS _author')
            ->leftJoin('users u', 'a.user_id = u.id')
            ->where('a.category_id')
            ->order('id DESC');
        $select->query(array($category->id));
        $stmt = $select->execute();
        $children = $stmt->fetchAll(\PDO::FETCH_CLASS, 'App\\Models\\Wiki\\Article', array(false));
        if (count($children) > 0) {
            return $children;
        }
    }

    /**
     *
     *
     * @param $id
     * @return bool|Article
     */
    static public function load($id) {
        $model = new Article();
        return $model->findOne($id);
    }
}