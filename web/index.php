<?php

require_once('../config/stratus/variables.php');
require_once('../app/init.php');

$domain = $_SERVER['SERVER_NAME'];

if (stripos($domain, 'stratus-cloudservices.com') !== false) {
    $env = 'prod';
} else {
    $env = 'dev';
}

App::newInstance($env);
