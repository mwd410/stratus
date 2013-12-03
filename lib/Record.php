<?php
/**
 * Created by PhpStorm.
 * User: matt.deady
 * Date: 11/21/13
 * Time: 10:34 PM
 */

abstract class Record {

    private $recordSchema;
    private $relationSchemas;
    private $table;
    private $data;
    public $rawData;

    public function __construct($record = null) {

        $this->setup();

        if ($record !== null) {

            $this->data = $this->hydrate($record);
        }
    }

    public function getData() {

        return $this->data;
    }

    public abstract function getSchema();

    public function setup() {

        $schema = $this->getSchema();

        $this->table = $schema['_table'];
        $this->setRecordSchema($schema['_record']);

        $this->identifyRelations();
    }

    private function identifyRelations() {

        foreach ($this->recordSchema as $key => $config) {

            if ($this->isRelation($config)) {

                $this->relationSchemas[$key] = $config;
            }
        }
    }

    private function setRecordSchema($schema) {

        $this->recordSchema = $schema;
    }

    public function hydrate($record, $schema = null) {

        if ($schema === null) {
            $schema = $this->recordSchema;
        }

        return $this->hydrateColumns($record, $schema);
    }

    private function hydrateColumns($record, $sourceInfo) {

        $result = array();

        foreach ($sourceInfo as $name => $source) {

            if (is_string($source)) {

                $this->hydrateFromString($result, $name, $record, $source);
            } else if (is_array($source)) {

                $this->hydrateFromArray($result, $name, $record, $source);
            }
        }

        return $result;
    }

    private function hydrateFromString(&$parent, $name, $record, $source) {

        $sourceInfo = explode(':', $source);

        $type = $sourceInfo[0];

        if (count($sourceInfo) === 1) {

            return $source;
        } else if ($type === 'field') {

            $dataType = $sourceInfo[1];
            $column = $sourceInfo[2];

            if (!array_key_exists($column, $record)) {
                return;
            }
            $raw = $record[$column];

            switch ($dataType) {
                case 'int':
                    $val = $raw === null ? $raw : intval($raw);
                    break;
                case 'float':
                    $val = $raw === null ? $raw : floatval($raw);
                    break;
                case 'double':
                    $val = $raw === null ? $raw : doubleval($raw);
                    break;
                case 'string':
                    //This will take care of null values too, so no need to check
                    $val = $raw;
                    break;
                case 'bool':
                    $val = $raw == '1';
                    break;
                default:
                    throw new Exception('Invalid data type ' . $dataType);
            }

            $parent[$name] = $val;
        }
    }

    public function isRelation($config) {

        if (!is_array($config)
            || !isset($config['_type'])
        ) {

            return false;
        } else {
            $typeInfo = explode(':', $config['_type']);

            return $typeInfo[0] === 'relation';
        }
    }

    private function hydrateFromArray(&$parent, $name, $record, $sourceInfo) {

        if (!isset($sourceInfo['_type'])) {

            $this->hydrateColumns($record, $sourceInfo);
        } else {
            $typeInfo = explode(':', $sourceInfo['_type']);
            $type = $typeInfo[0];

            if ($type === 'relation') {

                $relationType = $typeInfo[1];
                if ($relationType === 'one') {
                    $this->hydrateOne($parent, $name, $record, $sourceInfo);
                }
            }
        }
    }

    private function hydrateOne(&$parent, $name, $record, $sourceInfo) {

        $typeInfo = explode(':', $sourceInfo['_type']);
        $table = $typeInfo[2];

        // column on local table
        $local = isset($sourceInfo['_local'])
            ? $sourceInfo['_local']
            : 'id';

        //column on foreign table
        $foreign = isset($sourceInfo['_foreign'])
            ? $sourceInfo['_foreign']
            : 'id';

        $this->hydrateFromString($localKey, $name, $record, $local);
        $localKey = $localKey[$name];

        $relationSchema = $sourceInfo['_record'];

        $related = Query::select()
            ->column('*')
            ->from($table)
            ->where("$foreign = ?", $localKey)
            ->fetchOne();

        if ($related === null) {
            $parent[$name] = null;
        } else {
            $parent[$name] = $this->hydrate($related, $relationSchema);
        }
    }
} 
