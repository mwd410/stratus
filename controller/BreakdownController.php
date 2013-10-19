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

        $result['analysis'] = $this->getAnalysis($request);

        $this->json($result);
    }

    private $typeColumns = array(
        'provider' => array(
            'id'       => 'service_provider_id',
            'name'     => 'service_provider_name',
            'sub_id'   => 'service_product_id',
            'sub_name' => 'service_product_name'
        ),
        'type'     => array(
            'id'       => 'service_type_id',
            'name'     => 'service_type_name',
            'sub_id'   => 'service_type_category_id',
            'sub_name' => 'service_type_category',
        )
    );

    private $views = array(
        'provider' => 'service_provider_menu_v',
        'type'     => 'service_type_menu_v'
    );

    private function getColumn($type, $column) {

        if (!isset($this->typeColumns[$type])) {
            throw new Exception("Invalid type: '$type'.");
        }

        $columns = $this->typeColumns[$type];

        return $columns[$column];
    }

    private function getColumns($type) {

        if (!isset($this->typeColumns[$type])) {
            throw new Exception("Invalid type: '$type'.");
        }

        return $this->typeColumns[$type];
    }

    private function getView($type) {

        if (!isset($this->views[$type])) {
            throw new Exception("Invalid type: '$type'.");
        }

        return $this->views[$type];
    }

    private function getMenu(Request $request) {

        $type = $request->getParam('type');

        if ($type === false) {
            // We don't want to update.
            // send the least data possible.
            return 0;
        }

        $names = array(
            'provider' => 'Service Provider',
            'type'     => 'Service Type'
        );

        $items = array();

        foreach ($names as $itemType => $name) {
            $items[] = array(
                'name' => $name,
                'type' => $itemType
            );
        }

        $result = array(
            array(
                'name'  => 'Breakdown By',
                'items' => $items
            )
        );

        if ($type && isset($names[$type])) {

            $view = $this->getView($type);
            $columns = $this->getColumns($type);

            $customerId = $this
                ->getUser()
                ->get('customer_id');

            $menuQuery = Query::create(Query::SELECT)
                ->column('? as type', $type)
                ->from($view)
                ->where('customer_id = ?', $customerId);

            foreach ($columns as $alias => $column) {

                $menuQuery->column("$column as $alias");
            }

            $flatMenu = $menuQuery->execute();

            $menuItems = $this->flatToItems($flatMenu);

            $result[] = array(
                'name'  => $names[$type],
                'items' => $menuItems
            );
        }

        return $result;
    }

    private function flatToItems($menuItems) {

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

        array_unshift($items,
            array(
                'type' => $type,
                'name' => 'All',
                'id'   => null
            ));

        return $items;
    }

    public function getAnalysis(Request $request) {

        $type = $request->getParam('type');
        $id = $request->getParam('id');
        $subId = $request->getParam('sub_id');

        $customerId = $this->getUser()->get('customer_id');

        $analysisQuery = Query::create(Query::SELECT)
            ->column('history_date')
            ->column('sum(cost) as total')
            ->from('billing_history_v')
            ->where('customer_id = ?', $customerId)
            ->groupBy('history_date')
            ->orderBy('history_date asc')
            ->limit(30);

        if ($type !== null && $id !== null) {

            $idColumn = $this->getColumn($type, 'id');

            $analysisQuery->where($idColumn . ' = ?', $id);

            if ($subId !== null) {

                $subIdColumn = $this->getColumn($type, 'sub_id');

                $analysisQuery->where($subIdColumn . ' = ?', $subId);
            }
        }

        return $analysisQuery->execute();
    }
}