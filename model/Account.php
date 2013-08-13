<?php
/**
 * Created by JetBrains PhpStorm.
 * User: matt.deady
 * Date: 8/12/13
 * Time: 5:16 PM
 * To change this template use File | Settings | File Templates.
 */

class Account {

    /**
     * @return Query
     */
    public static function getBasicSelect() {
        $keyString = Config::from('config')->get('keystring');
        return Query::create(Query::SELECT)
            ->column('account_id as id')
            ->column("AES_DECRYPT(account_name,'$keyString') as name")
            ->column("AES_DECRYPT(aws_key,'$keyString') as aws_key")
            ->column("AES_DECRYPT(secret_key,'$keyString') as secret_key")
            ->from('account');
    }

    public static function find($id) {

        $keyString = Config::from('config')->get('keystring');
        $db = Database::getInstance();

        return;
    }
}