<?php
/**
 * Copyright (c) 2018.  MyArtSide 
 */

namespace App\Helper;

/**
 * Class Tree
 * @package Core\App\Backend\View
 
 */
class Tree {

    static protected $_controller;

    static protected $_action;

    static protected $_param;

    /**
     * Returns rendered tree
     *
     * @param $items
     * @param null $current
     * @return string
     */
    static public function output($items, $current = null) {
        return self::recursiveTree($items, 'tree', $current);
    }

    /**
     * Returns edit url
     *
     * @param $item
     * @return mixed
     */
    static protected function editUrl($item) {
        return '#';
    }

    /**
     * Returns full rendered tree
     *
     * @param $items
     * @param null $class
     * @param null $current
     * @return string
     */
    static protected function recursiveTree($items, $class = null, $current = null) {
        $html = '<ul class="'.$class.'">';
        if (is_array($items) === false) {
            $items = array();
        }
        foreach ($items as $item) {

            var_dump($item);
            $html .= '<li class="'.($current == $item->id ? 'active' : '').'">';
            $html .= '<a href="'.self::editUrl($item).'">';
            $html .= $item->title;
            $html .= '</a>';

            if ($current === $item->id) {
                $html .= ' <span class="fa fa-edit"></span>';
            }

            $labelClass = 'default';
            if ($item->route === 'home') {
                $labelClass = 'primary';
            }

            $html .= ' <span class="label label-'.$labelClass.' pull-right">'.$item->route.'</span>';

            if (!empty($item->children) && count($item->children) > 0) {
                $html .= self::recursiveTree($item->children, null, $current);
            }
            $html .=  '</li>';
        }
        $html .=  '</ul>';
        return $html;
    }
}