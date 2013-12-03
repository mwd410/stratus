<?php
/**
 * Created by PhpStorm.
 * User: matt.deady
 * Date: 12/3/13
 * Time: 8:38 AM
 */

class UserController extends Controller {

    public function infoAction(Request $request) {

        $builder = new ResponseBuilder();
        $builder->setData($this->getUser()->getAll());
        $this->json($builder->getResponse());
    }
} 
