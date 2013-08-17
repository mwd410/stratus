<?php


class DefaultController extends Controller {

    public function indexAction(Request $request) {

        $this->render('login');
    }

    public function registerAction(Request $request) {

        $email = $request->getParam('email');
        $password = $request->getParam('password');
        $confirm = $request->getParam('confirm');

        $success = false;
        if ($password !== $confirm) {

            $message = 'Passwords do not match.';
        } else {

            Query::create(Query::INSERT);
        }

        $result = array(
            'success' => $success,
            'message' => $message
        );
        $this->json($result);
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