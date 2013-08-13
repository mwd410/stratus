<?php
/**
 * Created by JetBrains PhpStorm.
 * User: matt.deady
 * Date: 8/13/13
 * Time: 8:55 AM
 * To change this template use File | Settings | File Templates.
 */

class Update extends Query {

    public function getSql() {

        $parts = $this->getAllParts();
        $parts = Utils::stripNotIn($parts,
        array(
             'from',
             'set',
             'where'
        ));

        array_unshift($parts, 'update');
        return implode(' ', $parts);
    }
}