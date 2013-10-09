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

        $menu = Query::create(Query::SELECT)
            ->column('*')
            ->from('service_type_menu_v')
            ->execute();

        $this->json($menu);
    }
}