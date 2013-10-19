<?php
/**
 * Created by JetBrains PhpStorm.
 * User: matt.deady
 * Date: 10/8/13
 * Time: 3:46 PM
 * To change this template use File | Settings | File Templates.
 */

class BreakdownController extends Controller {

    public function updateAction(Request $request) {

        $result = array();

        $result['menu'] = $this->getMenu($request);

        //$request['analysis'] = $this->getAnalysis($request);

        $this->json($result);
    }

    private function getMenu(Request $request) {

        $names = array(
            'provider' => 'Service Provider',
            'type'     => 'Service Type'
        );

        $items = array();

        foreach ($names as $type => $name) {
            $items[] = array(
                'name' => $name,
                'type' => $type
            );
        }

        $result = array(
            array(
                'name'  => 'Breakdown By',
                'items' => $items
            )
        );

        $type = $request->getParam('type');

        if ($type && isset($names[$type])) {

            $menuInfo = $this->getMenuInfo($type);

            $customerId = $this
                ->getUser()
                ->get('customer_id');

            $menuQuery = Query::create(Query::SELECT)
                ->column('? as type', $type)
                ->from($menuInfo['view'])
                ->where('customer_id = ?', $customerId);

            foreach ($menuInfo['columns'] as $column => $alias) {

                $menuQuery->column("$column as $alias");
            }

            $menuItems = $menuQuery->execute();

            $result[] = array(
                'name'  => $names[$type],
                'items' => $this->flatMenuToTree($menuItems)
            );
        }

        return $result;
    }

    private function flatMenuToTree($menuItems) {

        $items = array();
        $type = null;

        foreach ($menuItems as $item) {

            $id = $item['id'];

            if (!isset($items[$id])) {
                $items[$id] = array(
                    'id'   => $id,
                    'name' => $item['name'],
                    'type' => $item['type']
                );
            }

            if (!$type) {
                $type = $item['type'];
            }

            $item['name'] = $item['sub_name'];
            unset($item['sub_name']);

            $items[$id]['subItems'][] = $item;
        }

        $items = array_values($items);

        array_unshift($items, array(
            'type' => $type,
            'name' => 'All',
            'id'   => null
        ));

        return $items;
    }

    private function getMenuInfo($type) {

        $result = array();

        if ($type === 'provider') {
            $result['view'] = 'service_provider_menu_v';
            $result['columns'] = array(
                'service_provider_id'   => 'id',
                'service_provider_name' => 'name',
                'service_product_id'    => 'sub_id',
                'service_product_name'  => 'sub_name'
            );
        } else if ($type === 'type') {

            $result['view'] = 'service_type_menu_v';
            $result['columns'] = array(
                'service_type_id'          => 'id',
                'service_type_name'        => 'name',
                'service_type_category_id' => 'sub_id',
                'service_type_category'    => 'sub_name'
            );
        }

        return $result;
    }

    public function getAnalysis(Request $request) {

        $type = $request->getParam('type');
        $id = $request->getParam('id');
        $subId = $request->getParam('subId');


        if ($type === 'provider') {

        }

    }
}