<?php
/**
 * Created by JetBrains PhpStorm.
 * User: matt.deady
 * Date: 8/12/13
 * Time: 5:16 PM
 * To change this template use File | Settings | File Templates.
 */

class Account {

    public static $allColumns = array(
        'account_id',
        'customer_id',
        'name',
        'aws_key',
        'secret_key',
        'deleted'
    );

    const EXTERNAL_ID = 'external_id';
    const NAME = 'name';

    /**
     * @return string
     */
    public static function getKeyString() {

        return Config::from('config')
               ->get('keystring');
    }

    /**
     * @return Select
     */
    public static function getBasicSelect() {

        $keyString = self::getKeyString();

        return Query::create(Query::SELECT)
               ->column('id')
               ->column(self::EXTERNAL_ID)
               ->column("AES_DECRYPT(".self::NAME.",'$keyString') as name")
               ->column("AES_DECRYPT(aws_key,'$keyString') as aws_key")
               ->column("AES_DECRYPT(secret_key,'$keyString') as secret_key")
               ->from('account');
    }

    /**
     * @return Select
     */
    public static function getSelectAll() {
        return self::getBasicSelect()
               ->column('deleted')
               ->column('customer_id');
    }

    /**
     * @param $id
     *
     * @return Select
     */
    public static function find($id) {

        return self::getSelectAll()
               ->where('id = ?', $id);
    }

    /**
     * @param $data
     *
     * @return Update
     */
    public static function update($data) {

        $keyString = self::getKeyString();
        $q = Query::create(Query::UPDATE)
             ->from('account');

        foreach ($data as $key => $value) {

            $name = $key;

            switch ($name) {
                case 'aws_key':
                case 'secret_key':
                case 'name':
                    $q->set("$name = AES_ENCRYPT(?, '$keyString')", $value);
                    break;
                case 'customer_id':
                case self::EXTERNAL_ID:
                    $q->where("$name = ?", $value);
                    break;
                default:
                    break;
            }
        }

        return $q;
    }

    /**
     * @param $data
     *
     * @return Insert
     */
    public static function insert($data) {

        $keyString = self::getKeyString();
        $q = Query::create(Query::INSERT)
            ->from('account');

        $values = array();
        $params = array();

        foreach($data as $key => &$value) {

            $name = $key;

            switch ($name) {
                case 'aws_key':
                case 'secret_key':
                case self::NAME:
                    $values[] = "AES_ENCRYPT(?, '$keyString')";
                    break;
                case 'customer_id':
                case self::EXTERNAL_ID:
                case 'deleted':
                    $values[] = '?';
                    break;
                default:
                    continue;
            }
            $params[] = $value;
            $q->column($name);
        }
        $q->insert($values, $params);

        return $q;
    }

    public static function getErrors($data) {

        $errors = array();
        if (empty($data['name'])) {
            $errors['name'] = 'An account name is required';
        }

        if (!isset($data['aws_key']) || ($awsLength = strlen($data['aws_key']) !== 20)) {
            $errors['aws_key'] = "The AWS Key must be 20 characters long.";
        }

        if (!isset($data['secret_key']) || ($secretLength = strlen($data['secret_key']) !== 40)) {
            $errors['secret_key'] = "The Secret Key must be 40 characters long.";
        }

        return $errors;
    }

    public static function saveMaster($master, $customerId) {

        $db = Database::getInstance();

        if ($master['account_id'] == '0' || $master['account_id'] == null) {
            $sql = "delete from master_account
                    where customer_id = ?";
            $params = array($customerId);
        } else {
            $sql = "
            replace into master_account
            (account_id, customer_id, billing_bucket)
            values (?, ?, ?)";
            $params = array(
                $master['account_id'],
                $customerId,
                $master['billing_bucket']
            );
        }

        $db->execute($sql, $params);
    }

    public static function delete($id, $customerId) {

        Query::create(Query::UPDATE)
            ->from('account')
            ->set('deleted = 1')
            ->where('id = ?', $id)
            ->where('customer_id = ?', $customerId)
            ->execute();

        $sql = 'delete from master_account where account_id = ? and customer_id = ?';

        $params = array($id, $customerId);
        Database::getInstance()->execute($sql, $params);
    }
}
