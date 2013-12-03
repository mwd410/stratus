<?php
/**
 * Created by PhpStorm.
 * User: matt.deady
 * Date: 12/3/13
 * Time: 6:14 PM
 */

class Collection {

    private $model;
    private $data;

    public function __construct($model, $records = null) {

        $this->model = $model;
        $this->data = array();

        if ($records !== null) {
            $this->hydrate($records);
        }
    }

    public function hydrate(array $data) {

        foreach($data as $recordData) {
            $this->data[] = new $this->model($recordData);
        }
    }

    public function toArray() {

        $result = array();

        /** @var Record $record */
        foreach($this->data as $record) {
            $result[] = $record->getData();
        }

        return $result;
    }
} 
