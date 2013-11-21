<?php
/**
 * Created by PhpStorm.
 * User: matt.deady
 * Date: 11/19/13
 * Time: 5:52 PM
 */

class ServiceController extends Controller {

    public function indexAction(Request $request) {

        $customerId = $this->getUser()->get('customer_id');

        $providers = Query::create(Query::SELECT)
            ->column('*')
            ->from('service_provider_menu_v')
            ->where('customer_id = ' . $customerId)
            ->execute();

        $products = Query::create(Query::SELECT)
            ->column('*')
            ->from('service_type_menu_v')
            ->where('customer_id = ' . $customerId)
            ->execute();

        $result = array(
            'pivots' => array(
                array(
                    'id'    => '1',
                    'name'  => 'Service Provider',
                    'types' => $this->map($providers)
                ),
                array(
                    'id' => '2',
                    'name' => 'Service Type',
                    'types' => $this->map($products)
                )
            ),
            'comparisonTypes' => Query::create(Query::SELECT)
                    ->column('*')
                    ->from('alert_comparison_type')
                    ->execute(),
            'calculationTypes' => Query::create(Query::SELECT)
                    ->column('*')
                    ->from('alert_calculation_type')
                    ->execute(),
            'timeFrames' => Query::create(Query::SELECT)
                    ->column('*')
                    ->from('alert_time_frame')
                    ->execute(),
            'valueTypes' => Query::create(Query::SELECT)
                    ->column('*')
                    ->from('alert_value_type')
                    ->execute()
        );

        $this->json($result);
    }

    private function map($array) {

        $result = array();
        foreach ($array as $item) {

            $result[$item['type_id']]['id'] = $item['type_id'];
            $result[$item['type_id']]['name'] = $item['type_name'];
            $result[$item['type_id']]['sub_types'][] = array(
                'id' => $item['sub_type_id'],
                'name' => $item['sub_type_name']
            );
        }

        return array_values($result);
    }
}
