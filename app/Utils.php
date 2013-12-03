<?php
/**
 * Created by JetBrains PhpStorm.
 * User: matt.deady
 * Date: 8/7/13
 * Time: 7:44 PM
 * To change this template use File | Settings | File Templates.
 */

abstract class Utils {

    public static function mapValues($array, $key = 'id', $value = 'name') {

        return array_reduce($array, function($result, $item) use ($key, $value) {
            $result[$item[$key]] = $item[$value];
            return $result;
        }, array());
    }

    public static function apply(&$array, $values) {

        foreach ($values as $key => $value) {
            $array[$key] = $value;
        }
        return $array;
    }

    public static function applyIf(&$array, $values) {

        foreach ($values as $key => $value) {

            if (!isset($array[$key])) {
                $array[$key] = $value;
            }
        }

        return $array;
    }

    public static function buildDom($name, $attrs, $shortClose = true) {

        $parts = array($name);

        foreach ($attrs as $key => $value) {
            $parts[] = "$key=\"$value\"";
        }

        return '<' . implode(' ', $parts) . ($shortClose ? ' />' : "</$name>");
    }

    public static function stripNotIn($array, $keys) {

        foreach($array as $key => $value) {

            if (!in_array($key, $keys)) {
                unset($array[$key]);
            }
        }
        return $array;
    }

    public static function arrayIsIndexed($array) {

        $i = 0;
        foreach($array as $index => $value) {

            if ($index !== $i) {
                return false;
            }
            $i++;
        }
        return true;
    }
}
