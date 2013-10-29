<?php

class Select extends Query {

    public function execute($params = array(), $style = PDO::FETCH_ASSOC) {

        $result = $this->executeQuery($params);

        if (!$result) {

            $error = implode(': ', $this->getStatement()->errorInfo()) . $this;

            throw new Exception($error);
        }

        return $this
               ->getStatement()
               ->fetchAll($style);
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