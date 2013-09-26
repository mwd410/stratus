<?php


class DefaultController extends Controller {

    public function appAction(Request $request) {

        $this->render('app');
    }

    public function indexAction(Request $request) {

        $this->render('login');
    }

    public function registerAction(Request $request) {

        if ($request->getMethod() == 'GET') {

            $this->render('register');
        } else {

            $email = $request->getParam('email');
            $customerName = $request->getParam('customer_name');
            $password = $request->getParam('password');
            $confirm = $request->getParam('confirm');

            $success = false;
            $message = '';
            $available = $this->isEmailAvailable($email);

            $viewParams = array(
                'email'        => $email,
                'customerName' => $customerName
            );

            if (!$available['available']) {

                $message = 'Email is unavailable';
                unset($viewParams['email']);

            } else if ($password !== $confirm) {

                $message = 'Passwords do not match.';

            } else {

                $db = Database::getInstance();
                $db->begin();

                $createCustomer = Query::create(Query::INSERT)
                    ->from('customer')
                    ->column('name')
                    ->insert(array('?'), array($customerName));

                $createCustomer->execute();

                $customerId = $db->getConnection()->lastInsertId();
                $user = array(
                    'user_name'     => $email,
                    'email_address' => $email,
                    'password'      => sha1($password),
                    'customer_id'   => $customerId,
                    'deleted'       => '0'
                );

                $createUser = Query::create(Query::INSERT)
                    ->from('users');
                $values = array();
                foreach($user as $column => $value) {
                    $createUser->column($column);
                    $values[] = '?';
                }

                if ($createUser
                    ->insert($values, array_values($user))
                    ->execute()) {

                    $db->commit();
                    $success = true;
                } else {
                    $db->rollback();
                    $message = implode(': ', $createUser->getErrorInfo());
                }
            }

            if ($success) {
                $this->forward('/');
            } else {
                $viewParams['message'] = $message;
                $this->render('/register', $viewParams);
            }
        }
    }

    private function isEmailAvailable($email) {

        $existing = Query::create(Query::SELECT)
            ->column('*')
            ->from('users')
            ->where('email_address like ?', $email)
            ->execute();

        return array(
            'available' => empty($existing)
        );
    }

    public function isEmailAvailableAction(Request $request) {

        $email = $request->getParam('email');

        $result = $this->isEmailAvailable($email);
        $this->json($result);
    }

    public function loginAction(Request $request) {

        $email = $request->getParam('email');
        $password = $request->getParam('password');

        $success = $this->getUser()->authenticate($email, $password);

        if ($success) {
            $this->forward('/app');
        } else {
            $this->forward('/');
        }
    }

    public function logoutAction(Request $request) {

        $this->getUser()->logout();

        $this->forward('/');
    }
}