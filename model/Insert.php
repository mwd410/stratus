<?php
/**
 * Created by JetBrains PhpStorm.
 * User: matt.deady
 * Date: 8/12/13
 * Time: 8:09 PM
 * To change this template use File | Settings | File Templates.
 */

class Insert extends Query{

    private $onDupKey;

    public function __construct() {
        $this->onDupKey = '';
    }

    public function getSql() {
        $parts = $this->getAllParts();
        $parts = Utils::stripNotIn($parts,
            array(
                 'column',
                 'from',
                 'insert'
            ));

        if (isset($parts['column'])) {
            $parts['column'] = '('.$parts['column'].')';
        } else {
            $parts['column'] = '';
        }

        $sql = "INSERT INTO {$parts['from']} {$parts['column']} VALUES "
            .implode($parts['insert']) . ' '
            .$this->onDupKey;

        return $sql;
    }

    public function onDuplicateKey($action) {

        $this->onDupKey = 'on duplicate key '.$action;
    }
}