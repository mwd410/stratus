<?php
/**
 * Created by JetBrains PhpStorm.
 * User: matt.deady
 * Date: 8/10/13
 * Time: 10:53 AM
 * To change this template use File | Settings | File Templates.
 */

abstract class Record {

    const COL_TYPE_INT = 'int';
    const COL_TYPE_STRING = 'string';
    const COL_TYPE_BOOL = 'bool';

    const KEY_TYPE_PRI = 'PRI';
    const KEY_TYPE_FK = 'FK';

    protected $idColumn;
    protected $columns;
    protected $table;

    public function __construct() {

        $this->columns = array();
        $this->setup();
    }

    protected abstract function setup();

    protected function setTableName($tableName) {
        $this->table = $tableName;
    }

    public function defineColumns($columns) {

        foreach($columns as $column) {

            $this->addColumn($column);
        }
    }

    private function addColumn($column) {

        Utils::applyIf($column,
            array(
                 'id'          => false,
                 'aes_encrypt' => false,
                 'key'         => false
            ));

        if ($column['name'] === 'id' || $column['id'] === true) {
            $this->idColumn = $column['name'];
        }

        $this->columns[] = $column;
    }

    public function find($id) {

        $db = Database::getInstance();
        $selects = array();
        $keystring = Config::from('config')->get('keystring');

        foreach($this->columns as $column) {
            $name = $column['name'];
            if ($column['aes_encrypt'] === true) {
                $selects[] = "AES_DECRYPT($name, $keystring) as $name";
            } else {
                $selects[] = $column['name'];
            }
        }

        $columns = implode(',', $selects);
        $table = $this->table;
        $idColumn = $this->idColumn;

        $sql = "select $columns
                from $table
                where $idColumn = ?";

        $stmt = $db->prepare($sql);
        $stmt->execute(array($id));

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (isset($result[0])) {
            return $result[0];
        } else {
            return null;
        }
    }
}