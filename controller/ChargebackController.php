<?php
/**
 * Created by PhpStorm.
 * User: matt.deady
 * Date: 12/10/13
 * Time: 8:44 PM
 */

class ChargebackController extends Controller {

    public function indexAction(Request $request) {

        $builder = new ResponseBuilder();
        $builder->setData(array(
            array(
                'id'    => 1,
                'name'  => 'Matt Deady',
                'email' => 'm.deady410@gmail.com',
                'units' => array(
                    array(
                        'provider' => array(
                            'id'   => 1,
                            'name' => 'Amazon'
                        ),
                        'product'  => array(
                            'id'   => 1,
                            'name' => 'EC2'
                        ),
                        'account'  => array(
                            'id'   => 1,
                            'name' => 'Stratus Main'
                        ),
                        'email'    => ''
                    )
                )
            )
        ));
        $this->json($builder->getResponse());
    }
} 
