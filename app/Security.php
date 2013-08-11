<?php
/**
 * Created by JetBrains PhpStorm.
 * User: matt.deady
 * Date: 8/5/13
 * Time: 5:49 PM
 * To change this template use File | Settings | File Templates.
 */

class Security {

    private $config;

    public function __construct($config) {

        $this->config = $config;
    }

    /**
     * @param User $user
     *
     * @return boolean
     */
    public function checkAuthentication($user) {

        if (!$this->get('secure')) {
            $isAuthenticated = true;
        } else if ($user->isAuthenticated()) {
            $isAuthenticated = true;
        } else {
            $isAuthenticated = false;
        }

        return $isAuthenticated;
    }

    public function __get($name) {
        return $this->get($name);
    }

    public function get($name) {
        return $this->config[$name];
    }
}