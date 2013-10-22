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

        $result['projection'] = $this->getProjection($request);

        $this->json($result);
    }

    private $typeNames = array(
        'provider' => 'Service Provider',
        'type'     => 'Service Type'
    );

    private function getMenu(Request $request) {

        $type = $request->getParam('type');

        if ($type === false) {
            // We don't want to update.
            // send the least data possible.
            return 0;
        }

        $items = array();

        foreach ($this->typeNames as $itemType => $name) {
            $items[] = array(
                'name' => $name,
                'type' => $itemType,
                'title' => $name
            );
        }

        $result = array(
            array(
                'name'  => 'Breakdown By',
                'items' => $items
            )
        );

        if ($type && isset($this->typeNames[$type])) {

            $result[] = $this->getTypeMenu($type);
        }

        return $result;
    }

    private function getTypeMenu($type) {

        $views = array(
            'provider' => 'service_provider_menu_v',
            'type'     => 'service_type_menu_v'
        );

        $view = $views[$type];

        $customerId = $this
            ->getUser()
            ->get('customer_id');

        $menuQuery = Query::create(Query::SELECT)
            ->column('? as type', $type)
            ->column('type_id')
            ->column('type_name')
            ->column('sub_type_id')
            ->column('sub_type_name')
            ->from($view)
            ->where('customer_id = ?', $customerId);

        $flatMenu = $menuQuery->execute();

        $menuItems = $this->flatToItems($flatMenu);

        array_unshift($menuItems,
            array(
                'type'    => $type,
                'name'    => 'All',
                'type_id' => null,
                'title'   => $this->typeNames[$type]
            ));

        return array(
            'name'  => $this->typeNames[$type],
            'items' => $menuItems
        );
    }

    private function flatToItems($menuItems) {

        $items = array();

        foreach ($menuItems as $item) {

            $id = $item['type_id'];

            $titleParts = array(
                $this->typeNames[$item['type']],
                $item['type_name']
            );

            if (!isset($items[$id])) {
                $items[$id] = array(
                    'id'    => $id,
                    'name'  => $item['type_name'],
                    'type'  => $item['type'],
                    'title' => implode(' - ', $titleParts),
                );
            }

            $titleParts[] = $item['sub_type_name'];

            $items[$id]['subItems'][] = array(
                'type'   => $item['type'],
                'id'     => $id,
                'sub_id' => $item['sub_type_id'],
                'name'   => $item['sub_type_name'],
                'title'  => implode(' - ', $titleParts)
            );
        }

        $items = array_values($items);

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

            $columns = array(
                'provider' => array(
                    'id'     => 'service_provider_id',
                    'sub_id' => 'service_product_id'
                ),
                'type'     => array(
                    'id'     => 'service_type_id',
                    'sub_id' => 'service_type_category_id'
                )
            );

            $idColumn = $columns[$type]['id'];

            $analysisQuery->where($idColumn . ' = ?', $id);

            if ($subId !== null) {

                $subIdColumn = $columns[$type]['sub_id'];

                $analysisQuery->where($subIdColumn . ' = ?', $subId);
            }
        }

        return $analysisQuery->execute();
    }

    public function getProjection(Request $request) {

        $type = $request->getParam('type');
        $id = $request->getParam('id');
        $subId = $request->getParam('sub_id');

        if (!$type) {
            return false;
        }

        $customerId = $this->getUser()->get('customer_id');

        $typeViews = array(
            'provider' => 'service_provider_projection_v',
            'type'     => 'service_type_projection_v'
        );

        if (!isset($typeViews[$type])) {
            throw new Exception("Invalid type: '$type'.");
        }

        $projectionQuery = Query::create(Query::SELECT)
            ->column('sum(estimate) as projection')
            ->column('history_date as last_updated')
            ->from($typeViews[$type])
            ->where('customer_id = ?', $customerId);

        if ($id !== null) {

            $projectionQuery->where('type_id = ?', $id);

            if ($subId !== null) {

                $projectionQuery->where('sub_type_id = ?', $subId);
            }
        }

        $result = $projectionQuery->execute();

        if (count($result) > 0) {
            $result = $result[0];
        } else {
            $result = false;
        }

        return $result;
    }
}