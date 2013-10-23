<?php
/**
 * Created by JetBrains PhpStorm.
 * User: matt.deady
 * Date: 10/22/13
 * Time: 5:54 PM
 * To change this template use File | Settings | File Templates.
 */

class BillingHistoryView {

    private static $columns = array(
        'provider' => array(
            'id'     => 'service_provider_id',
            'sub_id' => 'service_product_id'
        ),
        'type'     => array(
            'id'     => 'service_type_id',
            'sub_id' => 'service_type_category_id'
        )
    );

    public static function getColumns($type){

        if (!isset(self::$columns[$type])) {
            throw new Exception('$type parameter null');
        }

        return self::$columns[$type];
    }
}