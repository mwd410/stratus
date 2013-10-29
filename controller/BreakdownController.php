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

        $result['widgets'] = $this->getWidgets($request);

        $result['title'] = $this->getTitle();

        $result['lastTitle'] = end($this->titleParts);

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

    public function getWidgets(Request $request) {

        $widgets = $request->getParam('widgets');

        if (is_string($widgets)) {
            $widgets = json_decode($widgets, true);
        }

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
                case 'dailyCost':
                    $widgetData = $this->getDailyCost($request, $widgetParams);
                    break;
                case 'monthToDate':
                    $widgetData = $this->getMonthToDate($request);
                    break;
                case 'monthlySpend':
                    $widgetData = $this->getMonthlySpend($request);
                    break;
                case 'rollingAverage':
                    $widgetData = $this->getRollingAverage($request,
                        $widgetParams);
                    break;
                case 'topSpend':
                    $widgetData = $this->getTopSpend($request);
                    break;
                default:
                    $widgetData = null;
                    break;
            }

            $result[$guid] = $widgetData;
        }

        return $result;
    }

    public function getDailyCost(Request $request, $params) {

        $type = $request->getParam('type');

        $customerId = $this->getUser()->get('customer_id');

        $analysisQuery = Query::create(Query::SELECT)
            ->column('history_date')
            ->column('format(ifnull(sum(cost), 0), 2) as total')
            ->from('billing_history_v')
            ->where('customer_id = ?', $customerId)
            ->where('history_date >= curdate() - interval 30 day')
            ->where('history_date < curdate()')
            ->groupBy('history_date')
            ->orderBy('history_date asc')
            ->limit(30);

        if ($type != null) {

            $columns = BillingHistoryView::getColumns($type);

            $result = $this->getFilteredResult($analysisQuery,
                $request,
                $columns);
        } else {
            $result = $analysisQuery->execute();
        }

        foreach ($result as &$row) {
            $row['total'] = floatval($row['total']);
        }

        return $result;
    }

    public function getMonthlySpend(Request $request) {

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
            ->column('format(ifnull(sum(estimate), 0), 2) as value')
            ->column('concat("Last Updated", history_date) as tooltip')
            ->column('"Projection" as label')
            ->from($typeViews[$type])
            ->where('customer_id = ?', $customerId);

        $columns = array(
            'id'     => 'type_id',
            'sub_id' => 'sub_type_id'
        );

        $result = $this->getFilteredResult($projectionQuery,
            $request,
            $columns);

        if (count($result) == 0) {
            $result = false;
        } else {

            $lastMonthQuery = Query::create(Query::SELECT)
                ->column('format(ifnull(sum(cost), 0), 2) as value')
                ->column('"Last Month" as label')
                ->from('billing_history_v')
                ->where('customer_id = ?', $customerId)
                ->where('month(history_date) = month(now() - interval 30 day)')
                ->where('year(history_date) = year(now() - interval 30 day)');

            $columns = BillingHistoryView::getColumns($type);

            $lastMonth = $this->getFilteredResult($lastMonthQuery,
                $request,
                $columns);

            $result = array_merge($result, $lastMonth);

            $spend = floatval($result[0]['value']);
            $lastSpend = floatval($result[1]['value']);

            if ($lastSpend == 0) {
                $diff = '0.00';
            } else {
                $diff = number_format((($spend - $lastSpend) / $lastSpend) * 100,
                    2);
            }

            foreach ($result as &$datum) {
                $datum['value'] = '$' . $datum['value'];
            }

            $result[] = array(
                'value' => $diff . '%',
                'label' => "MoM Change"
            );
        }

        return $result;
    }

    public function getMonthToDate(Request $request) {

        $type = $request->getParam('type');

        $customerId = $this->getUser()->get('customer_id');

        $query = Query::create(Query::SELECT)
            ->column('format(ifnull(sum(cost), 0), 2) as value')
            ->column('month(history_date) != month(now()) as month_index')
            ->column('date_format(history_date, "%M") as label')
            ->from('billing_history_v')
            ->where('customer_id = ?', $customerId)
            ->where('date_format(history_date, "%Y-%m") in (' .
                'date_format(now(), "%Y-%m"),' .
                'date_format(now() - interval 30 day, "%Y-%m"))')
            ->groupBy('month(history_date)')
            ->orderBy('history_date');

        if ($type) {

            $columns = BillingHistoryView::getColumns($type);

            $result = $this->getFilteredResult($query, $request, $columns);
        } else {

            $result = $query->execute();
        }

        $data = array(
            array(
                'value' => 0
            ),
            array(
                'value' => 0
            )
        );

        foreach ($result as $month) {

            $index = intval($month['month_index']);
            $data[$index]['value'] = floatval($month['value']);
            $data[$index]['label'] = $month['label'];
        }

        $thisMonth = $data[1]['value'];
        $lastMonth = $data[0]['value'];

        if ($lastMonth == 0) {
            $diff = '0.00';
        } else {
            $diff = number_format((($thisMonth - $lastMonth) / $lastMonth) * 100,
                2);
        }

        foreach ($data as &$datum) {
            $datum['value'] = '$' . $datum['value'];
        }

        $data[] = array(
            'value' => $diff . '%',
            'label' => 'MoM Change'
        );

        return $data;
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
            ->column('format(ifnull(sum(cost), 0), 2) as cost')
            ->column('history_date > (curdate() - interval ? day) as month_index',
                $days)
            ->from('billing_history_v')
            ->where('customer_id = ?', $customerId)
            ->where('history_date > curdate() - interval ? day', $days * 2)
            ->groupBy('history_date');

        $result = $this->getFilteredResult($query, $request, $columns);

        $data = array(
            array(
                'value' => 0,
                'sum'   => 0,
                'count' => 0,
                'label' => "Last $days days"
            ),
            array(
                'value' => 0,
                'sum'   => 0,
                'count' => 0,
                'label' => "Previous $days days"
            )
        );

        foreach ($result as $day) {

            $index = intval($day['month_index']);

            $data[$index]['sum'] += floatval($day['cost']);
            $data[$index]['count'] += 1;
        }

        foreach ($data as &$datum) {

            if ($datum['count'] == 0) {
                $avg = 0;
            } else {
                $avg = $datum['sum'] / $datum['count'];
            }

            $datum['value'] = $avg;
        }

        $first = $data[0]['value'];
        $second = $data[1]['value'];

        if ($second == 0) {
            $diff = '0.00';
        } else {
            $diff = number_format((($second - $first) / $first) * 100, 2);
        }

        $data[0]['value'] = '$' . number_format($data[0]['value'], 2);
        $data[1]['value'] = '$' . number_format($data[1]['value'], 2);

        $data[] = array(
            'value' => $diff . '%',
            'label' => 'Rolling Change'
        );

        return $data;
    }

    private function getTopSpend(Request $request) {

        $type = $request->getParam('type');
        $id = $request->getParam('id');
        $subId = $request->getParam('sub_id');

        if (!$type) {
            return null;
        }

        $columns = BillingHistoryView::getColumns($type);
        $keyString = Config::from('config')->get('keystring');

        $titles = array(
            'provider' => array(
                'id'     => 'Service Provider',
                'sub_id' => 'Service Provider Product'
            ),
            'type'     => array(
                'id'     => 'Service Type',
                'sub_id' => 'Service Type Category',
            )
        );

        $tables = array(
            array(
                'group'   => 'bhv.' . $columns['id'],
                'select'  => 'bhv.' . $columns['name'],
                'title'   => $titles[$type]['id'],
                'include' => $id == null
            ),
            array(
                'group'   => 'bhv.' . $columns['sub_id'],
                'select'  => 'bhv.' . $columns['sub_name'],
                'title'   => $titles[$type]['sub_id'],
                'include' => $subId == null
            ),
            array(
                'group'   => 'account_id',
                'select'  => "aes_decrypt(a.name, '$keyString')",
                'title'   => 'Account',
                'include' => true
            )
        );

        $data = array();

        foreach ($tables as $table) {

            if (!$table['include']) {
                continue;
            }

            $groupColumn = $table['group'];
            $selectColumn = $table['select'];

            $query = Query::create(Query::SELECT)
                ->column($selectColumn . ' as name')
                ->column('format(sum(bhv.cost), 2) as total')
                ->from('billing_history_v bhv')
                ->join('account a on a.id = bhv.account_id')
                ->where('a.deleted = 0')
                ->groupBy($groupColumn);

            if ($id != null) {

                $idColumn = 'bhv.' . $columns['id'];

                $query->where($idColumn . ' = ?', $id);

                if ($subId != null) {

                    $subIdColumn = 'bhv.' . $columns['sub_id'];

                    $query->where($subIdColumn . ' = ?', $subId);
                }
            }

            $result = $query->execute(array(), PDO::FETCH_NUM);

            $data[] = array(
                'headers' => array(
                    $table['title'],
                    'Spend'
                ),
                'data'    => $result
            );
        }

        return $data;
    }

}