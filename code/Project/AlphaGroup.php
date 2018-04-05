<?php
/**
 * Copyright (c) 2018.  MyArtSide 
 */

namespace Project;

use App\Models\Project;

class AlphaGroup {

    public $name;
    public $projects = array();

    /**
     * AlphaGroup constructor.
     * @param $key
     * @param Project[] $rows
     */
    public function __construct($key, $rows) {
        $this->name = $key;
        $checkKey = strtolower($key);
        foreach ($rows as $project) {
            if (strpos($project->name, $checkKey) === 0) {
                $this->projects[] = $project;
                continue;
            }
        }
    }

    /**
     *
     *
     * @return mixed
     */
    public function getKey() {
        return $this->name;
    }

    /**
     *
     *
     * @return bool
     */
    public function hasProjects() {
        return (count($this->projects) > 0);
    }

    /**
     *
     *
     * @return Project[]
     */
    public function getProjects() {
        return $this->projects;
    }

    /**
     *
     *
     * @return string
     */
    public function render() {
        $class = 'btn btn-default btn-sm';
        if ($this->hasProjects() === false) {
            $class .= ' disabled';
        }
        $href = '#' . $this->getKey();
        return sprintf('<a href="%s" class="%s">%s</a>', $href, $class, $this->getKey());
    }
}