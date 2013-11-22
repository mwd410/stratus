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

    private function hydrateColumns($record, $sourceInfo) {

        $result = array();

        foreach ($sourceInfo as $name => $source) {

            if (is_string($source)) {

                $result[$name] = $this->hydrateFromString($record, $source);
            } else if (is_array($source)) {

                $result[$name] = $this->hydrateFromArray($record, $source);
            }
        }

        return $result;
    }

    private function hydrateFromArray($record, $sourceInfo) {

        if (!isset($record['_type'])) {

            return $this->hydrateColumns($record, $sourceInfo);
        } else {
            return null;
        }
    }
} 
