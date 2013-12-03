<?php
/**
 * Created by PhpStorm.
 * User: matt.deady
 * Date: 11/19/13
 * Time: 5:52 PM
 */

class PivotController extends Controller {

    public function indexAction(Request $request) {

        $customerId = $this->getUser()->get('customer_id');

        $providers = Query::create(Query::SELECT)
            ->column('*')
            ->from('service_provider_menu_v')
            ->where('customer_id = ' . $customerId)
            ->execute();

        $types = Query::create(Query::SELECT)
            ->column('*')
            ->from('service_type_menu_v')
            ->where('customer_id = ' . $customerId)
            ->execute();

        $result = array(
            'pivotTypes' => Query::selectAllFrom('pivot_type'),
            'provider'   => $providers,
            'type'       => $types
        );

        $builder = new ResponseBuilder();
        $builder->setData($result);

        $this->json($builder->getResponse());
    }
}
