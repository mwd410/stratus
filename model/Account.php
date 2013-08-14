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
        'account_name',
        'aws_key',
        'secret_key',
        'deleted'
    );

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
               ->column('account_id as id')
               ->column("AES_DECRYPT(account_name,'$keyString') as name")
               ->column("AES_DECRYPT(aws_key,'$keyString') as aws_key")
               ->column("AES_DECRYPT(secret_key,'$keyString') as secret_key")
               ->from('account');
    }

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
               ->where('account_id = ?', $id);
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
            if ($name == 'name') {
                $name = 'account_name';
            } else if ($name == 'id') {
                $name = 'account_id';
            }


            switch ($name) {
                case 'aws_key':
                case 'secret_key':
                case 'account_name':
                    $q->set("$name = AES_ENCRYPT(?, '$keyString')", $value);
                    break;
                case 'customer_id':
                case 'account_id':
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
            if ($name == 'name') {
                $name = 'account_name';
            } else if ($name == 'id') {
                $name = 'account_id';
            }


            switch ($name) {
                case 'aws_key':
                case 'secret_key':
                case 'account_name':
                    $values[] = "AES_ENCRYPT(?, '$keyString')";
                    break;
                case 'customer_id':
                case 'account_id':
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
}