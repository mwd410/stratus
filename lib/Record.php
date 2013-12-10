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

    public function __construct($record) {

        $this->setup();
        $this->data = $this->hydrate($record);
    }

    public abstract function getSchema();
    
    public function getValidation() {
        return array();
    }

    private function validateString($value, $validation) {

        $errors = array();
        if ($value === null) {
            if (!isset($validation['allowNull'])
                || $validation['allowNull'] === false) {

                $errors[] = 'Cannot be null.';
            }
        } else {

            if (!is_string($value)) {

                $errors[] = 'Must be a string';
            }

            if (isset($validation['minLength'])
                && strlen($value) < $validation['minLength']) {

                $errors[] = 'Must be at least '
                    . $validation['minLength']
                    . ' characters long.';
            }

            if (isset($validation['maxLength'])
                && strlen($value) < $validation['maxLength']) {

                $errors[] = 'Cannot be longer than '
                    . $validation['maxLength']
                    . ' characters.';
            }
        }

        return $errors;
    }

    private function validateField($value, $validation) {

        $type = $validation['type'];

        switch($type) {
            case 'string';
                return $this->validateString($value, $validation);
            default:
                return array();
        }
    }

    public function validate(array $params, ResponseBuilder $builder = null) {
        
        $validation = $this->getValidation();
        
        foreach($params as $field => $value) {
        
            if (isset($validation[$field])) {
                $errors = $this->validateField($value, $validation[$field]);

                foreach($errors as $error) {
                    $builder->addError($field, $error);
                }
            }
        }
    }

    public function setup() {

        $schema = $this->getSchema();

        $this->table = $schema['_table'];
        $this->setRecordSchema($schema['_record']);
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
                    $val = intval($raw);
                    break;
                case 'string':
                    $val = $raw;
                    break;
                case 'bool':
                    $val = $raw === '1';
                    break;
                default:
                    throw new Exception('Invalid data type ' . $dataType);
            }

            $parent[$name] = $val;
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
