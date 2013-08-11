<?php
/**
 * Created by JetBrains PhpStorm.
 * User: matt.deady
 * Date: 8/3/13
 * Time: 10:38 AM
 * To change this template use File | Settings | File Templates.
 */

class App {

    private static $instance;
    private static $env;
    private $user;
    private $routing;

    public static function newInstance($env) {

        if (isset(self::$instance)) {
            return self::$instance;
        }

        self::$env = $env;

        self::$instance = new static($env);
    }

    public static function getEnv() {
        return self::$env;
    }

    private function __construct($env) {

        session_start();

        if (!isset($_SESSION)) {
            $_SESSION = array();
        }
        $this->user = new User($_SESSION);
        $this->routing = new Routing($this->user);

        $url = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
        $this->routing->route($url);
    }
}