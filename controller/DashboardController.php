<?php
/**
 * Created by JetBrains PhpStorm.
 * User: matt.deady
 * Date: 9/8/13
 * Time: 5:57 PM
 * To change this template use File | Settings | File Templates.
 */

class DashboardController extends Controller {

    public function indexAction(Request $request) {

        $this->render('dashboard');
    }
}