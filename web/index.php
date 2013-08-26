<?php

require_once('../config/stratus/variables.php');
require_once('../app/init.php');

$domain = $_SERVER['SERVER_NAME'];

if ($domain == 'webapp.stratus-cloudservices.com') {
    $env = 'prod';
} else {
    $env = 'dev';
}

App::newInstance($env);
