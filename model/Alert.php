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
                'id'                => 'field:string:id',
                'name'              => 'field:string:name',
                'pivotTypeId'       => 'field:string:pivot_type_id',
                'accountId'         => 'field:string:account_id',
                'providerId'        => 'field:string:service_provider_id',
                'productId'         => 'field:string:service_provider_product_id',
                'serviceTypeId'     => 'field:string:service_type_id',
                'categoryId'        => 'field:string:service_type_category_id',
                'comparisonTypeId'  => 'field:string:comparison_type_id',
                'calculationTypeId' => 'field:string:calculation_type_id',
                'timeFrameId'       => 'field:string:time_frame_id',
                'valueTypeId'       => 'field:string:value_type_id',
                'threshold'         => 'field:float:threshold',
                'inEmail'           => 'field:bool:in_email',
                'inBreakdown'       => 'field:bool:in_breakdown',
                'email'             => 'field:string:email'
                /*
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
                )*/
            )
        );
    }

    public function getLabel() {

    }
} 
