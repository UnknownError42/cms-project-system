<?php
namespace App\Models;

use Core\Model;

class Project extends Model {

    const STATUS_CLASS_SUCCESS = 'success';
    const STATUS_CLASS_WARNING = 'warning';
    const STATUS_CLASS_ERROR = 'danger';

    const STATUS_SUCCESS = 'success';
    const STATUS_WARNING = 'warning';
    const STATUS_ERROR = 'error';

    public $id;
    public $name;
    public $core = 'cms';
    public $domain;
    public $docroot;
    public $created;
    public $created_by;

    public $_table = 'domains';

    protected $_tickets;

    protected $_statusName = array(
        0 => 'new',
        1 => 'ready4qa',
        2 => 'warnings',
        3 => 'errors',
        4 => 'ready4live'
    );

    protected $_statusClass = array(
        0 => 'default',
        1 => 'info',
        2 => 'warning',
        3 => 'danger',
        4 => 'success'
    );

    /**
     *
     *
     * @param $name
     * @return Project
     * @throws \Exception
     */
    static public function get($name) {
        $select = new Model\Query\Select('domains');
        $select->columns('*')
            ->where('name');
        $select->query(array($name));
        $stmt = $select->execute();
        return $stmt->fetchObject('App\\Models\\Project', array(false));
    }

    /**
     *
     *
     * @param $status
     * @param $priority
     * @return Task[]
     */
    protected function getTasksBy($status, $priority = null) {
        $rows = array();
        foreach ($this->getTasks() as $task) {
            if ((int)$task->status === $status) {
                if ($priority !== null) {
                    if ((int)$task->priority !== $priority) {
                        continue;
                    }
                }
                $rows[] = $task;
            }
        }
        return $rows;
    }

    /**
     *
     *
     * @return bool|string
     */
    public function getDbUrl() {
        $filename = str_replace("/public", "/data/db/default.sql3", $this->docroot);
        if (is_file($filename)) {
            $url = 'http://udev/adminer.php?sqlite=&username=&db=' . urlencode($filename);
            return $url;
        }
        return false;
    }

    /**
     *
     *
     * @return Task[]
     */
    public function getTasks() {
        if (null === $this->_tickets) {
            $this->_tickets = Task::getAllByDomain($this->id);
        }
        return $this->_tickets;
    }

    /**
     *
     *
     * @return bool
     */
    public function hasTasks() {
        return (count($this->getTasks()) > 0);
    }

    /**
     *
     *
     * @return bool
     */
    public function hasErrors() {
        return (count($this->getTasksBy(0, 2)) > 0);
    }

    /**
     *
     *
     * @return bool
     */
    public function hasWarnings() {
        return (count($this->getTasksBy(0, 1)) > 0);
    }

    /**
     *
     *
     * @return string
     */
    public function getStatusName() {
        if ($this->hasErrors()) {
            return self::STATUS_ERROR;
        }
        if ($this->hasWarnings()) {
            return self::STATUS_WARNING;
        }
        return self::STATUS_SUCCESS;
    }

    /**
     *
     *
     * @return string
     */
    public function getStatusClass() {
        if ($this->hasErrors()) {
            return self::STATUS_CLASS_ERROR;
        }
        if ($this->hasWarnings()) {
            return self::STATUS_CLASS_WARNING;
        }
        return self::STATUS_CLASS_SUCCESS;
    }

    /**
     *
     *
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    public function getType() {
        $core = $this->core;
        if ($core === 'mage') {
            $core = 'shop';
        }
        return $core;
    }

    /**
     *
     *
     * @return mixed
     */
    public function getUrl() {
        return $this->domain;
    }

    /**
     *
     *
     * @return string
     */
    public function getExternalUrl() {
        return 'http://' . $this->domain;
    }

    /**
     *
     *
     * @return bool|string
     */
    public function getCreatedDate() {
        if ((int)$this->created > 0) {
            return date("d.m.Y H:i", $this->created);
        }
        return '---';
    }

    public function isMovable() {
        return ($this->core === 'cms');
    }
}