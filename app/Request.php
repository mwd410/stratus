<?php
/**
 * Created by JetBrains PhpStorm.
 * User: matt.deady
 * Date: 7/30/13
 * Time: 12:09 AM
 * To change this template use File | Settings | File Templates.
 */

class Request {

    private $params;

    public function __construct($params = array()) {

        $this->setParams($params);
        $this->setParams($_GET);
        $this->setParams($_POST);

        $requestBody = file_get_contents('php://input');
        if ($requestBody) {
            $this->setParams(json_decode($requestBody, true));
        }
    }

    public function setParam($name, $value) {

        $this->params[$name] = $value;
    }

    public function setParams($params) {
        foreach($params as $name => $value) {
            $this->setParam($name, $value);
        }
    }

    public function getParam($name) {
        return $this->params[$name];
    }

    public function getParams() {
        return $this->params;
    }
}