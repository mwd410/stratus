<?php

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