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
    private $method;

    public function __construct($params = array()) {

        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);

        $this->setParams($params);

        if ($this->method === 'GET') {
            $this->setParams($_GET);
        } else if ($this->method === 'POST') {

            $requestBody = file_get_contents('php://input');
            $requestBody = json_decode($requestBody, true);

            if (is_array($requestBody)) {
                $this->setParams($requestBody);
            } else {
                $this->setParams($_POST);
            }
        }
    }

    public function getMethod() {
        return $this->method;
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