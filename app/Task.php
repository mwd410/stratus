<?php
/**
 * Created by JetBrains PhpStorm.
 * User: matt.deady
 * Date: 7/31/13
 * Time: 6:10 PM
 * To change this template use File | Settings | File Templates.
 */

abstract class Task {

    private static $config;

    private $db;
    private $params;
    private $description;

    /**
     * @return Config
     */
    public static function getConfig() {

        if (!isset(self::$config)) {
            self::$config = Config::from('task');
        }

        return self::$config;
    }

    public static function beginTask($args, $options) {

        $config = self::getConfig();

        $name = $args[1];
        $taskName = $config->get($name);

        $Class = $taskName . 'Task';

        $task = new $Class(array_slice($args, 2), $options);
        $params = $task->getParamValues($options);
        $task->run($args, $params);
    }

    private function __construct() {

        $this->params = array();
        $this->setup();
    }

    protected function addParam($p, $_ = null) {

        $params = func_get_args();
        foreach($params as $param) {

            if (!isset($param['name'])) {
                throw new Exception('You must specify a name for each parameter.');
            }

            if (isset($this->params[$param['name']])) {
                throw new Exception('Duplicate parameter name '.$param['name']);
            }

            $this->params[$param['name']] = $param;
        }
    }

    protected function setDescription($desc) {
        $this->description = $desc;
    }

    protected function getDB() {

        if (!isset($this->db)) {
            $this->db = Database::getInstance();
        }

        return $this->db;
    }

    protected function getParamValues($options) {

        foreach($options as $name => $value) {


        }
    }

    protected abstract function setup();
    protected abstract function run($cliArgs, $options);

}