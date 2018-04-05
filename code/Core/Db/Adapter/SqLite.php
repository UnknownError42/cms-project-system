<?php
namespace Core\Db\Adapter;

use Core\Db\Adapter;
use Core\Db\Pdo;

/**
 * Class SqLite
 * @package Core\Db\Adapter
 
 */
class SqLite extends Adapter {

    /**
     * Returns PDO instance
     *
     * @return Pdo
     */
    public function getPDO() {
        $dns = $this->_options['dns'];
        if (!is_file($dns)) {
            mkdir(dirname($dns), 0777, true);
        }
        if (!is_file($dns)) {
            file_put_contents($dns, '');
        }
        return new Pdo('sqlite:' . $dns);
    }
}