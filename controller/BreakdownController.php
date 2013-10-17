<?php
/**
 * Created by JetBrains PhpStorm.
 * User: matt.deady
 * Date: 10/8/13
 * Time: 3:46 PM
 * To change this template use File | Settings | File Templates.
 */

class BreakdownController extends Controller {

    public function menuAction(Request $request) {

        $type = $request->getParam('type');

        $menuInfo = $this->getMenuInfo($type);

        if (empty($menuInfo)) {
            $this->json(array(
                             'success' => false,
                             'message' => ''
                        ));
        }

        $menuQuery = Query::create(Query::SELECT)
                     ->from($menuInfo['view']);

        foreach ($menuInfo['columns'] as $column => $alias) {

            $menuQuery->column("$column as $alias");
        }

        $menu = $menuQuery->execute();

        $this->json($menu);
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
}