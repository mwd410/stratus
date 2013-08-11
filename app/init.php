<?php

function __autoload($className) {

    $names = array(
        "$className.php",
        "$className.class.php",
        "class.$className.php"
    );

    $dirs = array(
        '../app',
        '../controller',
        '../lib'
    );

    foreach($dirs as $dir) {
        foreach($names as $name) {
            $path = dirname(__FILE__)."/$dir/$name";
            if (is_file($path)) {
                require_once($path);
                break;
            }
        }
    }
}

