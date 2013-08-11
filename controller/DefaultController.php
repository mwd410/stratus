<?php


class DefaultController extends Controller {

    public function indexAction(Request $request) {

        $this->render('login');
    }

    public function loginAction(Request $request) {

        $username = $request->getParam('username');
        $password = $request->getParam('password');

        $success = $this->getUser()->authenticate($username, $password);

        if ($success) {
            $this->forward('/accounts');
        } else {
            $this->forward('/');
        }
    }

    public function logoutAction(Request $request) {

        $this->getUser()->logout();

        $this->forward('/');
    }
}