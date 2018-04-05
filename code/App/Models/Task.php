<?php
namespace App\Models;

use Core\Model;

class Task extends Model {


    public $id;
    public $domains_id;
    public $priority;
    public $status;
    public $title;
    public $content;

    public $_table = 'task';

    protected $_priorityName = array(
        0 => 'simple',
        1 => 'warning',
        2 => 'error',
    );

    protected $_statusName = array(
        0 => 'open',
        1 => 'closed',
    );

    /**
     *
     *
     * @param $id int
     * @return Task[]
     * @throws \Exception
     */
    static public function getAllByDomain($id) {
        $select = new Model\Query\Select('task');
        $select->columns('*')
            ->where('domains_id')
            ->order('id DESC');
        $select->query(array($id));
        $stmt = $select->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, 'App\\Models\\Task', array(false));
    }
}