<?php

class User {

    private $data;
    private $authenticated;

    public function authenticate($email, $password) {

        $db = Database::getInstance();

        $sql = 'select * from user where email_address = ? and password = ? and deleted = 0';
        $params = array(
            $email,
            sha1($password));

        $result = $db->fetchAll($sql, $params);

        $this->authenticated = count($result) > 0;

        if ($this->isAuthenticated()) {
            $this->setup($result[0]);
        }

        return $this->isAuthenticated();
    }

    public function logout() {

        $this->clearData();
        $this->authenticated = false;
    }

    public function __destruct() {

        $this->persist();
    }

    public function __construct($session) {

        $this->data = array();
        if (isset($session['user']) && isset($_SESSION['user']['data'])) {

            $this->apply($session['user']['data']);
        }

        $this->authenticated = isset($session['user']['authenticated']) ? $session['user']['authenticated'] : false;
    }

    public function isAuthenticated() {
        return $this->authenticated;
    }

    public function setup($sqlUser) {

        $this->set('id', $sqlUser['id']);
        $this->set('username', $sqlUser['user_name']);
        $this->set('email', $sqlUser['email_address']);
        $this->set('customer_id', $sqlUser['customer_id']);

        $this->persist();
    }

    public function get($name) {

        if (!isset($this->data[$name])) {
            $value = null;
        } else {
            $value = $this->data[$name];
        }

        return $value;
    }

    public function __get($name) {

        return $this->get($name);
    }

    public function set($name, $value) {

        $this->data[$name] = $value;

        return $this;
    }

    public function __set($name, $value) {

        return $this->set($name, $value);
    }

    public function apply($data) {

        foreach ($data as $name => $value) {
            $this->set($name, $value);
        }
        return $this;
    }

    public function clearData() {
        $this->data = array();
    }

    public function persist() {

        $_SESSION['user'] = array(
            'data' => $this->data,
            'authenticated' => $this->isAuthenticated()
        );
    }

}