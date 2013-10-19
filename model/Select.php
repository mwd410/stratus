<?php

class Select extends Query {

    public function execute($params = array()) {

        $result = parent::execute($params);

        if (!$result) {

            $errors = $this->getStatement()->errorInfo();
            $errors[] = $this->getSql();

            throw new Exception(implode(': ', $errors));
        }

        return $this
               ->getStatement()
               ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSql() {

        $parts = $this->getAllParts();
        $parts = Utils::stripNotIn($parts,
            array(
                'column',
                'from',
                'join',
                'where',
                'group',
                'having',
                'order',
                'limit',
            ));

        $parts['from'] = 'from '.$parts['from'];
        $select = 'select ' . ($this->isDistinct ? 'DISTINCT ' : '');
        return $select . implode(' ', $parts);
    }
}