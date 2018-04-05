<?php
namespace App\Models;

use Core\Model\Query\Select;

class Stats {

    public function getRanking() {
        $select = new Select('domains', 'd');
        $select->columns('COUNT(d.id) as items, u.fullname')
            ->leftJoin('users u', 'd.created_by = u.id')
            ->where('d.created_by', null, 'IS NOT NULL')
            ->group('d.created_by')
            ->order('items DESC');

        $select->query(array());
        $stmt = $select->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, 'App\\Models\\StatsRow', array(false));
    }

}