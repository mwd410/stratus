<?php
/**
 * Created by JetBrains PhpStorm.
 * User: matt.deady
 * Date: 7/30/13
 * Time: 10:47 PM
 * To change this template use File | Settings | File Templates.
 */

class Database {

    private $pdo;
    /** @var  Config */
    private static $config;
    private static $instance;

    public static function getInstance() {

        if (!isset(self::$config)) {
            self::$config = Config::from('db');
        }
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    private function __construct() {

        $dsn = self::$config->get('dsn');
        $username = self::$config->get('username');
        $password = self::$config->get('password');

        $this->pdo = new PDO($dsn, $username, $password);
    }

    public function getConnection() {

        return $this->pdo;
    }

    public function fetchAll($sql, $params = array(), $style = PDO::FETCH_ASSOC) {

        $stmt = $this->pdo->prepare($sql);
        if (!$stmt->execute($params)) {
            throw new Exception(implode(': ', $stmt->errorInfo()));
        }
        return $stmt->fetchAll($style);
    }

    /**
     * @param      $sql
     * @param null $options
     *
     * @return PDOStatement
     */
    public function prepare($sql, $options = null) {
        return $this->getConnection()->prepare($sql, $options);
    }

    public function execute($sql, $params = array()) {

        $stmt = $this->pdo->prepare($sql);
        if (!$stmt->execute($params)) {
            throw new Exception(implode(': ', $stmt->errorInfo()));
        }
        return true;
    }

    public function begin() {
        return $this->getConnection()->beginTransaction();
    }

    public function commit() {
        return $this->getConnection()->commit();
    }

    public function rollback() {
        return $this->getConnection()->rollBack();
    }
}