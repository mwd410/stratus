<?php
/**
 * Created by PhpStorm.
 * User: matt.deady
 * Date: 12/14/13
 * Time: 5:18 PM
 */

class ArgumentQuery {

    private $parts = array();
    private $params = array();

    public function __construct($arguments) {

        foreach($arguments as $index => $arg) {

            if ($index % 2 === 0) {
                $this->parts[] = $arg;
            } else if (is_array($arg)) {
                $this->params = array_merge($this->params, $arg);
            } else {
                $this->params[] = $arg;
            }
        }


    }
} 
