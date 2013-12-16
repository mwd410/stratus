<?php
/**
 * Created by PhpStorm.
 * User: matt.deady
 * Date: 12/10/13
 * Time: 8:44 PM
 */

class ChargebackController extends Controller {

    public function infoAction(Request $request) {

        $customerId = $this->getUser()->get('customer_id');

        $accountProducts = Query::select('billing_history_v')
            ->isDistinct()
            ->column('account_id')
            ->column('service_provider_id')
            ->column('service_provider_name')
            ->column('service_product_id as service_provider_product_id')
            ->column('service_provider_product_name')
            ->where('customer_id = ' . $customerId)
            ->execute();

        $builder = new ResponseBuilder();
        $builder->setData(array(
            'accountProducts' => $accountProducts
        ));

        $this->json($builder->getResponse());
    }

    public function indexAction(Request $request) {

        $customerId = $this->getUser()->get('customer_id');

        $builder = new ResponseBuilder();
        $builder->setData(array(
            'stakeholders' => Query::select('stakeholder')
                    ->where('customer_id = ' . $customerId)
                    ->execute(),
            'chargeback'  => Query::select('chargeback_v')
                    ->where('customer_id = ' . $customerId)
                    ->execute()
        ));

        $this->json($builder->getResponse());
    }
} 
