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

    private $parts;
    private $params;
    private $stmt;
    protected $isDistinct;

    public static function delete($from = null) {

        $query = new Delete();

        if ($from !== null) {
            $query->from($from);
        }

        return $query;
    }

    /**
     * @return Select
     */
    public static function select() {

        return self::create(self::SELECT);
    }
    /**
     * @param $type
     *
     * @return $this
     * @throws Exception
     */
    public static function create($type) {

        switch($type) {
            case self::SELECT:
                return new Select();
                break;
            case self::UPDATE:
                return new Update();
                break;
            case self::INSERT:
                return new Insert();
                break;
            case self::DELETE:
                return new Delete();
                break;
            default:
                throw new Exception('You must provide a $type.');
        }
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

    private function getAllParams($lastParams = array()) {

        $allParams = array();

        foreach($this->params as $params) {
            $allParams = array_merge($allParams, $params);
        }
        return array_merge($allParams, $lastParams);
    }

    private function getColumnPart() {

        return implode(', ', $this->parts['column']);
    }

    private function getFromPart() {

        return $this->parts['from'][0];
    }

    private function getSetPart() {

        return 'set '.implode(', ', $this->parts['set']);
    }

    private function getInsertPart() {
        $valueList = array();
        foreach($this->parts['insert'] as $values) {
            $valueList[] = "(".implode(", ", $values) . ")";
        }
        return implode(', ', $valueList);
    }

    private function getJoinPart() {

        return implode(' ', $this->parts['join']);
    }

    private function getWherePart() {

        return 'where ' . implode(' AND ', $this->parts['where']);
    }

    private function getGroupPart() {

        return 'group by ' . implode(', ', $this->parts['group']);
    }

    private function getOrderPart() {

        return 'order by ' . implode(', ', $this->parts['order']);
    }

    private function getLimitPart() {

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
