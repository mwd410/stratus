<?php
/**
 * Created by PhpStorm.
 * User: matt.deady
 * Date: 11/16/13
 * Time: 12:40 PM
 */

class AlertController extends Controller {

    public function indexAction(Request $request) {

        $customerId =$this->getUser()->get('id');

        $alerts = Query::create(Query::SELECT)
            ->column('*')
            ->from('alert')
            ->where('user_id = '.$customerId)
            ->execute();

        $this->json(array(
            'alerts' => $alerts
        ));
    }
} 
