<?php
/**
 * Created by JetBrains PhpStorm.
 * User: matt.deady
 * Date: 8/12/13
 * Time: 5:20 PM
 * To change this template use File | Settings | File Templates.
 */

abstract class Query {

    const SELECT = 0;
    const UPDATE = 1;
    const INSERT = 2;
    const DELETE = 3;

    protected $parts;
    protected $params;
    protected $stmt;
    protected $isDistinct;

    private static $staticStmt;

    public static function stmt() {

        return self::$staticStmt;
    }

    public static function begin() {

        Database::getInstance()->begin();
    }

    public static function commit() {

        Database::getInstance()->commit();
    }

    public static function rollback() {

        Database::getInstance()->rollback();
    }

    public static function lastInsertId($table) {

        return Database::getInstance()
            ->getConnection()
            ->lastInsertId($table);
    }

    /**
     * @param $type
     *
     * @return $this
     * @throws Exception
     */
    public static function create($type, $from = null) {

        switch($type) {
            case self::INSERT:
                $query = new Insert();
                break;
            case self::SELECT:
                $query = new Select();
                break;
            case self::UPDATE:
                $query = new Update();
                break;
            case self::DELETE:
                $query =  new Delete();
                break;
            default:
                throw new Exception('You must provide a $type.');
        }

        $query->from($from);

        return $query;
    }

    public static function executeStmt($str1, $param1 = null) {

        $arguments = func_get_args();

        $parts = array();
        $params = array();

        foreach ($arguments as $index => $arg) {

            if ($index % 2 === 0) {
                $parts[] = $arg;
            } else if (is_array($arg)) {
                $params = array_merge($params, $arg);
            } else {
                $params[] = $arg;
            }
        }

        $sql = implode(' ', $parts);
        self::$staticStmt = Database::getInstance()->getConnection()->prepare($sql);

        if (Config::from('config')->get('sql_log') === true) {

            App::log('sql', $sql . '[' . implode(',', $params) . ']');
        }

        return self::$staticStmt->execute($params);
    }

    public static function insertInto($from = null) {

        return self::create(self::INSERT, $from);
    }

    /**
     * @param null $from
     *
     * @return Select
     */
    public static function select($from = null) {

        return self::create(self::SELECT, $from);
    }

    public static function update($from = null) {

        return self::create(self::UPDATE, $from);
    }

    public static function delete($from = null) {

        return self::create(self::DELETE, $from);
    }

    public static function selectAllFrom($from) {

        return self::select($from)
            ->column('*')
            ->execute();
    }

    public function __construct() {

        $this->isDistinct = false;

        $this->parts = array(
            'column' => array(),
            'from'   => array(),
            'set'    => array(),
            'insert' => array(),
            'join'   => array(),
            'where'  => array(),
            'group'  => array(),
            'order'  => array(),
            'limit'  => array(),
        );

        $this->params = $this->parts;
    }

    public abstract function getSql();

    /**
     * @return PDOStatement
     */
    public function getStatement() {
        return $this->stmt;
    }

    public function getErrorInfo() {
        return $this->getStatement()->errorInfo();
    }

    /**
     * @param array $params
     *
     * @return bool|array
     */
    public function execute($params = array()) {

        return $this->executeQuery($params);
    }

    public function executeQuery($params = array()) {

        $sql = $this->getSql();
        $db = Database::getInstance();

        $this->stmt = $db->getConnection()->prepare($sql);

        if (Config::from('config')->get('sql_log') === true) {
            App::log('sql', $this);
        }

        return $this->stmt->execute($this->getAllParams($params));
    }

    public function addPart($part, $value) {

        if (is_array($value)) {
            $this->parts[$part] = array_merge($this->parts[$part], $value);
        } else {
            $this->parts[$part][] = $value;
        }
    }

    public function setPart($part, $value) {

        $this->parts[$part][0] = $value;
    }

    public function addParams($part, $params) {

        if (count($params) === 1 && is_array($params[0])) {

            $params = $params[0];
        }

        $this->params[$part] = array_merge($this->params[$part], $params);
    }

    /**
     * @return $this
     */
    public function isDistinct() {
        $this->isDistinct = true;
        return $this;
    }

    /**
     * @param      $column
     * @param null $params
     *
     * @return $this
     */
    public function column($column, $params = null) {

        $this->addPart('column', $column);
        $this->addParams('column', array_slice(func_get_args(), 1));

        return $this;
    }

    /**
     * @param $columns
     *
     * @return $this
     */
    public function columns(array $columns) {

        foreach($columns as $alias => $column) {
            $this->column($column . ' as ' . $alias);
        }

        return $this;
    }

    /**
     * @param      $from
     * @param null $params
     *
     * @return $this
     */
    public function from($from, $params = null) {

        $this->setPart('from', $from);
        $this->addParams('from', array_slice(func_get_args(), 1));

        return $this;
    }

    /**
     * @param      $set
     * @param null $params
     *
     * @return $this
     */
    public function set($set, $params = null) {

        $this->addPart('set', $set);
        $this->addParams('set', array_slice(func_get_args(), 1));

        return $this;
    }

    public function setAll($values) {

        foreach($values as $key => $value) {

            $this->set("$key = ?", $value);
        }

        return $this;
    }

    /**
     * @param      $data
     * @param null $params
     *
     * @return $this
     */
    public function insert($data, $params = null) {

        $this->setPart('insert', $data);
        $this->addParams('insert', array_slice(func_get_args(), 1));

        return $this;
    }

    /**
     * @param      $join
     * @param null $params
     *
     * @return $this
     */
    public function join($join, $params = null) {

        $this->addPart('join', 'join ' . $join);
        $this->addParams('join', array_slice(func_get_args(), 1));

        return $this;
    }

    /**
     * @param      $join
     * @param null $params
     *
     * @return $this
     */
    public function leftJoin($join, $params = null) {

        $this->addPart('join', 'left join ' . $join);
        $this->addParams('join', array_slice(func_get_args(), 1));

        return $this;
    }

    /**
     * @param      $where
     * @param null $params
     *
     * @return $this
     */
    public function where($where, $params = null) {

        $this->addPart('where', $where);
        $this->addParams('where', array_slice(func_get_args(), 1));

        return $this;
    }

    /**
     * @param      $group
     * @param null $params
     *
     * @return $this
     */
    public function groupBy($group, $params = null) {

        $this->addPart('group', $group);
        $this->addParams('group', array_slice(func_get_args(), 1));

        return $this;
    }

    /**
     * @param      $order
     * @param null $params
     *
     * @return $this
     */
    public function orderBy($order, $params = null) {

        $this->addPart('order', $order);
        $this->addParams('order', array_slice(func_get_args(), 1));

        return $this;
    }

    /**
     * @param      $limit
     * @param null $params
     *
     * @return $this
     */
    public function limit($limit, $params = null) {

        $this->setPart('limit', $limit);
        $this->addParams('order', array_slice(func_get_args(), 1));

        return $this;
    }

    /**
     * @return array
     */
    protected function getAllParts() {

        $parts = array();
        foreach($this->parts as $name => $part) {

            if (count($part) > 0) {
                $partFn = 'get'.ucfirst($name).'Part';
                $parts[$name] = $this->$partFn();
            }
        }

        return $parts;
    }

    protected function getAllParams($lastParams = array()) {

        $allParams = array();

        foreach($this->params as $params) {
            $allParams = array_merge($allParams, $params);
        }
        return array_merge($allParams, $lastParams);
    }

    protected function getColumnPart() {

        return implode(', ', $this->parts['column']);
    }

    protected function getFromPart() {

        return $this->parts['from'][0];
    }

    protected function getSetPart() {

        return 'set '.implode(', ', $this->parts['set']);
    }

    protected function getInsertPart() {
        $valueList = array();
        foreach($this->parts['insert'] as $values) {
            $valueList[] = "(".implode(", ", $values) . ")";
        }
        return implode(', ', $valueList);
    }

    protected function getJoinPart() {

        return implode(' ', $this->parts['join']);
    }

    protected function getWherePart() {

        return 'where ' . implode(' AND ', $this->parts['where']);
    }

    protected function getGroupPart() {

        return 'group by ' . implode(', ', $this->parts['group']);
    }

    protected function getOrderPart() {

        return 'order by ' . implode(', ', $this->parts['order']);
    }

    protected function getLimitPart() {

        return 'limit ' . $this->parts['limit'][0];
    }

    public function __toString() {

        $sql = $this->getSql();
        $sql = trim($sql);

        if (substr($sql, -1) !== ';') {
            $sql .= ';';
        }

        $params = $this->getAllParams();

        foreach($params as $index => $param) {

            if (is_null($param)) {
                $params[$index] = 'NULL';
            } else if ($param === false) {
                $params[$index] = '0';
            }
        }

        return $sql . ' [' . implode(', ',$params) . ']';
    }

}
