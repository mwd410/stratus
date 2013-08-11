<?php
/**
 * Created by JetBrains PhpStorm.
 * User: matt.deady
 * Date: 8/10/13
 * Time: 10:54 AM
 * To change this template use File | Settings | File Templates.
 */

class Account extends Record {

    public function setup() {

        $this->setTableName('account');

        $columns = array(
            array(
                'id'      => true,
                'name'    => 'account_id',
                'type'    => self::COL_TYPE_INT,
                'null'    => false,
                'default' => null,
                'key'     => self::KEY_TYPE_PRI
            ),
            array(
                'name'    => 'customer_id',
                'type'    => self::COL_TYPE_INT,
                'null'    => false,
                'default' => null
            ),
            array(
                'name'        => 'account_name',
                'type'        => self::COL_TYPE_STRING,
                'null'        => false,
                'default'     => null,
                'aes_encrypt' => true
            ),
            array(
                'name'        => 'aws_key',
                'type'        => self::COL_TYPE_STRING,
                'null'        => false,
                'default'     => null,
                'aes_encrypt' => true
            ),
            array(
                'name'        => 'secret_key',
                'type'        => self::COL_TYPE_STRING,
                'null'        => false,
                'default'     => null,
                'aes_encrypt' => true
            ),
            array(
                'name'    => 'deleted',
                'type'    => self::COL_TYPE_BOOL,
                'null'    => false,
                'default' => false
            )
        );

        $this->defineColumns($columns);
    }
}