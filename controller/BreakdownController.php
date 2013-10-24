<?php
/**
 * Created by JetBrains PhpStorm.
 * User: matt.deady
 * Date: 10/8/13
 * Time: 3:46 PM
 * To change this template use File | Settings | File Templates.
 */

class BreakdownController extends Controller {

    private $titleParts = array();

    public function updateAction(Request $request) {

        $result = array();

        $result['menu'] = $this->getMenu($request);

        $result['title'] = $this->getTitle();

        $result['analysis'] = $this->getAnalysis($request);

        $result['widgets'] = $this->getWidgets($request);

        $this->json($result);
    }

    private function addTitlePart($part) {

        $this->titleParts[] = $part;
    }

    private function getTitle() {

        return implode(' - ', $this->titleParts);
    }

    private $typeNames = array(
        'provider' => 'Service Provider',
        'type'     => 'Service Type'
    );

    public function getFilteredResult(Query $query, Request $request, array $columns) {

        $id = $request->getParam('id');
        $subId = $request->getParam('sub_id');

        if ($id != null) {

            $idColumn = $columns['id'];

            $query->where($idColumn . ' = ?', $id);

            if ($subId != null) {

                $subIdColumn = $columns['sub_id'];

                $query->where($subIdColumn . ' = ?', $subId);
            }
        }

        return $query->execute();
    }

    private function getMenu(Request $request) {

        $type = $request->getParam('type');

        if ($type === false) {
            // We don't want to update.
            // send the least data possible.
            return 0;
        }

        $items = array();

        foreach ($this->typeNames as $itemType => $name) {
            $itemIsActive = $itemType == $type;
            $items[] = array(
                'name'     => $name,
                'type'     => $itemType,
                'title'    => $name,
                'isActive' => $itemIsActive
        );

            if ($itemIsActive) {
                $this->addTitlePart($name);
            }
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

        $allItem = array(
            'type'     => $type,
            'name'     => 'All',
            'title'    => $this->typeNames[$type],
            'isActive' => !$this->getRequest()->getParam('id')
        );

        array_unshift($menuItems, $allItem);

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

                if ($isActive) {
                    $this->addTitlePart($items[$id]['name']);
                }
            }

            $titleParts[] = $item['sub_type_name'];

            $isActive = $isActive && $item['sub_type_id'] == $request->getParam('sub_id');

            $subItem = array(
                'type'     => $item['type'],
                'id'       => $id,
                'sub_id'   => $item['sub_type_id'],
                'name'     => $item['sub_type_name'],
                'title'    => implode(' - ', $titleParts),
                'isActive' => $isActive
            );

            $items[$id]['subItems'][] = $subItem;

            if ($isActive) {
                $this->addTitlePart($subItem['name']);
            }
        }

        $items = array_values($items);

        return $items;
    }

    public function getAnalysis(Request $request) {

        $type = $request->getParam('type');

        $customerId = $this->getUser()->get('customer_id');

        $analysisQuery = Query::create(Query::SELECT)
            ->column('history_date')
            ->column('format(ifnull(sum(cost), 0), 2) as total')
            ->from('billing_history_v')
            ->where('customer_id = ?', $customerId)
            ->groupBy('history_date')
            ->orderBy('history_date asc')
            ->limit(30);


        if ($type != null) {

            $columns = BillingHistoryView::getColumns($type);

            return $this->getFilteredResult($analysisQuery, $request, $columns);
        }

        return $analysisQuery->execute();
    }

    public function getWidgets(Request $request) {

        $widgets = $request->getParam('widgets');

        $widgets = json_decode($widgets, true);

        if (is_null($widgets)) {
            return null;
        }

        if (!is_array($widgets)) {
            $widgets = array($widgets);
        }
        $result = array();

        foreach ($widgets as $guid => $widget) {

            $widgetType = $widget['type'];
            $widgetParams = $widget['params'];

            switch ($widgetType) {
                case 'lastMonthSpend':
                    $widgetData = $this->getLastMonthSpend($request);
                    break;
                case 'eomProjection':
                    $widgetData = $this->getProjection($request);
                    break;
                case 'rollingAverage':
                    $widgetData = $this->getRollingAverage($request,
                        $widgetParams);
                    break;
                default:
                    $widgetData = null;
                    break;
            }

            $result[$guid] = $widgetData;
        }

        return $result;
    }

    public function getProjection(Request $request) {

        $type = $request->getParam('type');

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

        $columns = array(
            'id'     => 'type_id',
            'sub_id' => 'sub_type_id'
        );

        $result = $this->getFilteredResult($projectionQuery,
            $request,
            $columns);

        if (count($result) > 0) {
            $result = $result[0];
        } else {
            $result = false;
        }

        return $result;
    }

    public function getLastMonthSpend(Request $request) {

        $type = $request->getParam('type');

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

        $result = $this->getFilteredResult($query, $request, $columns);

        $result = $result[0];

        return $result;
    }

    public function getRollingAverage(Request $request, array $params) {

        $type = $request->getParam('type');
        $days = $params['days'];

        if (!$type || !is_numeric($days)) {
            return false;
        }

        $columns = BillingHistoryView::getColumns($type);
        $customerId = $this->getUser()->get('customer_id');

        $query = Query::create(Query::SELECT)
            ->column('ifnull(sum(cost), 0) as cost')
            ->from('billing_history_v')
            ->where('customer_id = ' . $customerId)
            ->where('month(history_date) + 1 = month(now())')
            ->groupBy('history_date');

        $result = $this->getFilteredResult($query, $request, $columns);

        $sum = 0;

        foreach ($result as $row) {
            $sum += $row['cost'];
        }

        return array(
            'kpi' => '$' . number_format($sum / (count($result) ? : 1), 2)
        );
    }

}