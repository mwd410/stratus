<?php
/**
 * Created by PhpStorm.
 * User: matt.deady
 * Date: 12/2/13
 * Time: 5:13 PM
 */

class ResponseBuilder {

    private $success;
    private $data;
    private $errors;
    private $warnings;

    public function __construct() {

        $this->errors = new stdClass();
        $this->warnings = new stdClass();
        $this->data = null;
    }

    public function addError($key, $error) {

        if (isset($this->errors->$key)) {
            throw new Exception('Already have error key '.$key);
        }

        $this->errors->$key = $error;
    }

    public function addWarning($key, $warning) {

        if (isset($this->warnings->$key)) {
            throw new Exception('Already have warning key '.$key);
        }

        $this->warnings->$key = $warning;
    }

    public function getResponse() {

        return array(
            'success'  => empty($this->errors) ? $this->success : false,
            'data'     => $this->data,
            'errors'   => $this->errors,
            'warnings' => $this->warnings
        );
    }

    public function setData($data) {

        $this->data = $data;
    }
} 
