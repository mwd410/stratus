<?php
/**
 * Created by PhpStorm.
 * User: matt.deady
 * Date: 11/21/13
 * Time: 10:35 PM
 */

class Alert extends Record {

    public static $columns = array(
        'id',
        'user_id',
        'name',
        'pivot_type_id',
        'account_id',
        'service_provider_id',
        'service_provider_product_id',
        'service_type_id',
        'service_type_category_id',
        'comparison_type_id',
        'calculation_type_id',
        'time_frame_id',
        'value_type_id',
        'threshold',
        'in_email',
        'in_breakdown',
        'email'
    );

    public function getSchema() {

        return array(
            '_table'  => 'alert',
            '_record' => array(
                'id'            => 'field:int:id',
                'name'          => 'field:string:name',
                'pivotTypeId'   => 'field:int:alert_classification_type_id',
                'pivotType'     => array(
                    '_type'     => 'relation:one:alert_classification_type',
                    '_local'    => 'field:int:alert_classification_type_id',
                    '_record'   => array(
                        'id'    => 'field:int:id',
                        'name'  => 'field:string:name'
                    )
                ),
                'accountId'     => 'field:int:account_id',
                'providerId'    => 'field:int:service_provider_id',
                'productId'     => 'field:int:service_provider_product_id',
                'serviceTypeId' => 'field:int:service_type_id',
                'categoryId'    => 'field:int:service_type_category_id',
                'sendEmail'     => 'field:bool:send_email',
                'displayIn'     => array(
                    'overview'  => 'field:bool:overview',
                    'breakdown' => 'field:bool:breakdown'
                ),
                'email'         => array(
                    'send'    => 'field:bool:send_email',
                    'address' => 'field:string:email_address'
                )
            )
        );
    }

    public static function getDescription($alert) {


    }
} 
