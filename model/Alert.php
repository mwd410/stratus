<?php
/**
 * Created by PhpStorm.
 * User: matt.deady
 * Date: 11/21/13
 * Time: 10:35 PM
 */

class Alert extends Record {

    public function getSchema() {

        return array(
            '_table'  => 'alert',
            '_record' => array(
                'id'            => 'field:int:id',
                'name'          => 'field:string:name',
                'pivotType'     => 'field:int:alert_classification_type_id',
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

    public function __construct($record) {

        $this->setup();
        $this->hydrate($record);
    }
} 
