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

        $this->data = $this->hydrateColumns($record, $this->recordSchema);
    }

    private function hydrateColumns($record, $sourceInfo) {

        $result = array();

        foreach ($sourceInfo as $name => $source) {

            if (is_string($source)) {

                $this->hydrateFromString($result, $name, $record, $source);
            } else if (is_array($source)) {

                $this->hydrateFromArray($parent, $name, $record, $source);
            }
        }

        return $result;
    }

    private function hydrateFromString(&$parent, $name, $record, $source) {

        $sourceInfo = explode(':', $source);

        $type = $sourceInfo[0];

        if ($type === 'field') {

            $dataType = $sourceInfo[1];
            $column = $sourceInfo[2];

            if (!array_key_exists($column, $record)) {
                return;
            }
            $raw = $record[$column];

            switch($dataType) {
                case 'int':
                    $val = intval($raw);
                    break;
                case 'string':
                    $val = $raw;
                    break;
                case 'bool':
                    $val =  $raw === '1';
                    break;
            }

            $parent[$name] = $val;
        }
    }

    private function hydrateFromArray(&$parent, $name, $record, $sourceInfo) {

        if (!isset($record['_type'])) {

            return $this->hydrateColumns($record, $sourceInfo);
        } else {
            return null;
        }
    }
} 
