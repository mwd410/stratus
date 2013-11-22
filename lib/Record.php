<?php
/**
 * Created by PhpStorm.
 * User: matt.deady
 * Date: 11/21/13
 * Time: 10:34 PM
 */

abstract class Record {

    private $recordSchema;
    private $table;
    public $data;

    public abstract function getSchema();

    public function setup() {

        $schema = $this->getSchema();

        $table = $schema['_table'];
        $this->setRecordSchema($schema['_record']);
    }

    private function setRecordSchema($schema) {

        $this->recordSchema = $schema;
    }

    public function hydrate($record) {

        foreach($this->recordSchema as $name => $source) {

            if (is_string($source)) {

                $this->data[$name] = $this->hydrateFromString($record, $source);
            }
        }
    }

    private function hydrateFromString($record, $source) {

        $sourceInfo = explode(':', $source);

        $type = $sourceInfo[0];
        $dataType = $sourceInfo[1];
        $column = $sourceInfo[2];

        if ($type === 'field') {

            $raw = $record[$column];

            switch($dataType) {
                case 'int':
                    return intval($raw);
                case 'string':
                    return $raw;
                case 'bool':
                    return $raw === '1';
            }
        }
    }
} 
