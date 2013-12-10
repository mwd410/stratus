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

        $this->success = true;
        $this->data = null;
        $this->errors = array();
        $this->warnings = array();
    }

    public function addError($field, $error = null) {

        if ($error === null) {
            $error = $field;
            $field = '_';
        }

        $this->errors[$field][] = $error;
    }

    public function addWarning($field, $warning = null) {

        if ($warning === null) {
            $warning = $field;
            $field = '_';
        }

        $this->warnings[$field][] = $warning;
    }

    public function getResponse() {

        $tmpErrors = (array)$this->errors;
        return array(
            'success'  => empty($tmpErrors) ? $this->success : false,
            'data'     => $this->data,
            'errors'   => $this->errors,
            'warnings' => $this->warnings
        );
    }

    public function setData($data) {

        $this->data = $data;
    }

    public function setSuccess($success) {

        $this->success = !!$success;
    }
} 
