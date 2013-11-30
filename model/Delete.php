<?php
/**
 * Created by PhpStorm.
 * User: matt.deady
 * Date: 11/30/13
 * Time: 12:26 PM
 */

class Delete extends Query {

    public function getSql() {

        $parts = $this->getAllParts();
        $parts = Utils::stripNotIn($parts, array(
            'from',
            'where',
            'order',
            'limit'
        ));

        $parts['from'] = 'from '.$parts['from'];
        return 'delete '.implode(' ', $parts);
    }
}
