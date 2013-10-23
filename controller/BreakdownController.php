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

        $result['widgets'] = $this->getWidgets($request);

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
                'name'     => $name,
                'type'     => $itemType,
                'title'    => $name,
                'isActive' => $itemType == $type
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
                'type'     => $type,
                'name'     => 'All',
                'title'    => $this->typeNames[$type],
                'isActive' => !$this->getRequest()->getParam('id')
            ));

        return array(
            'name'  => $this->typeNames[$type],
            'items' => $menuItems
        );
    }

    private function flatToItems($menuItems) {

        $items = array();
        $request = $this->getRequest();

        foreach ($menuItems as $item) {

            $id = $item['type_id'];

            $titleParts = array(
                $this->typeNames[$item['type']],
                $item['type_name']
            );

            $isActive = $id == $request->getParam('id');

            if (!isset($items[$id])) {
                $items[$id] = array(
                    'id'       => $id,
                    'name'     => $item['type_name'],
                    'type'     => $item['type'],
                    'title'    => implode(' - ', $titleParts),
                    'isActive' => $isActive,
                );
            }

            $titleParts[] = $item['sub_type_name'];

            $isActive = $isActive && $item['sub_type_id'] == $request->getParam('sub_id');

            $items[$id]['subItems'][] = array(
                'type'     => $item['type'],
                'id'       => $id,
                'sub_id'   => $item['sub_type_id'],
                'name'     => $item['sub_type_name'],
                'title'    => implode(' - ', $titleParts),
                'isActive' => $isActive
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
            ->column('format(ifnull(sum(cost), 0), 2) as total')
            ->from('billing_history_v')
            ->where('customer_id = ?', $customerId)
            ->groupBy('history_date')
            ->orderBy('history_date asc')
            ->limit(30);

        if ($type !== null && $id !== null) {

            $columns = BillingHistoryView::getColumns($type);

            $idColumn = $columns['id'];

            $analysisQuery->where($idColumn . ' = ?', $id);

            if ($subId !== null) {

                $subIdColumn = $columns['sub_id'];

                $analysisQuery->where($subIdColumn . ' = ?', $subId);
            }
        }

        return $analysisQuery->execute();
    }

    public function getWidgets(Request $request) {

        $widgets = $request->getParam('widgets');

        if (!is_array($widgets)) {
            $widgets = array($widgets);
        }
        $result = array();

        foreach ($widgets as $widget) {

            switch ($widget) {
                case 'lastMonthSpend':
                    $widgetData = $this->getLastMonthSpend($request);
                    break;
                case 'eomProjection':
                    $widgetData = $this->getProjection($request);
                    break;
                default:
                    $widgetData = null;
                    break;
            }

            $result[$widget] = $widgetData;
        }

        return $result;
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
            ->column('concat("$", format(ifnull(sum(estimate), 0), 2)) as kpi')
            ->column('concat("Last Updated: ", history_date) as kpi_title')
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

    public function getLastMonthSpend(Request $request) {

        $type = $request->getParam('type');
        $id = $request->getParam('id');
        $subId = $request->getParam('sub_id');

        if (!$type) {
            return false;
        }

        $columns = BillingHistoryView::getColumns($type);
        $customerId = $this->getUser()->get('customer_id');

        $query = Query::create(Query::SELECT)
            ->column('concat("$", format(ifnull(sum(cost), 0), 2)) as kpi')
            ->from('billing_history_v')
            ->where('customer_id = ?', $customerId)
            ->where('month(history_date) + 1 = month(now())');

        if ($id != null) {

            $idColumn = $columns['id'];

            $query->where($idColumn . ' = ?', $id);

            if ($subId != null) {

                $subIdColumn = $columns['sub_id'];

                $query->where($subIdColumn . ' = ?', $subId);
            }
        }

        $result = $query->execute();

        $result = $result[0];

        return $result;
    }
}