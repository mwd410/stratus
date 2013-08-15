<?php
/**
 * Created by JetBrains PhpStorm.
 * User: matt.deady
 * Date: 8/1/13
 * Time: 5:19 PM
 * To change this template use File | Settings | File Templates.
 */

class AccountController extends Controller {

    public function mainAction(Request $request) {

        $this->render('mainAccount');
    }

    public function getAccountsAction(Request $request) {

        $customerId = $this->getUser()->get('customer_id');

        $accounts = Account::getBasicSelect()
            ->where("customer_id = ?", $customerId)
            ->where('deleted = 0')
            ->execute();

        $masterAccount = Query::create(Query::SELECT)
            ->column('account_id')
            ->column('billing_bucket')
            ->from('master_account')
            ->where('customer_id = ?', $customerId)
            ->execute();

        $result = array(
            'accounts' => $accounts,
            'masterAccount' => empty($masterAccount) ? null : $masterAccount
        );
        $this->json($result);
    }

    public function addAction(Request $request) {

        $db = Database::getInstance();
        $customerId = $this->getUser()->get('customer_id');

        $params = Utils::stripNotIn($request->getParams(),
            array('id', 'name', 'aws_key', 'secret_key'));
        $params['customer_id'] = $customerId;

        $q = Account::insert($params);

        $db->begin();

        if (!$q->execute()) {
            $db->rollback();
            $result = array(
                'success' => false,
                'message' => implode(': ', $q->getErrorInfo())
            );
        } else {
            $db->commit();
            unset($params['customer_id']);

            $result = array(
                'success' => true,
                'message' => 'Account created.',
                'data'    => $params
            );
        }
        $this->json($result);
    }

    public function editAction(Request $request) {

        $id = $request->getParam('id');
        $db = Database::getInstance();
        $customerId = $this->getUser()->get('customer_id');

        $existing = Account::find($id)
                    ->execute();
        $success = false; // false unless everything works out.
        $errors = array();

        if (empty($existing)) {

            $message = 'Account not found';
        } else {

            $existing = $existing[0];

            if ($existing['customer_id'] != $customerId) {

                $message = 'Sorry, you do not have access to this account.';
            } else {

                $params = $request->getParams();
                $params['customer_id'] = $customerId;

                $errors = Account::getErrors($params);

                if (!empty($errors)) {

                    $message = 'Account save failed with errors.';
                } else {
                    $db->begin();

                    $q = Account::update($params);

                    if (!$q->execute()) {
                        $stmt = $q->getStatement();
                        $message = implode(': ', $stmt->errorInfo());
                    } else {
                        $success = true;
                        $message = $params['name'] . ' saved.';
                    }

                    if ($success === true) {
                        $db->commit();
                    } else {
                        $db->rollBack();
                    }
                }
            }
        }
        $result = array(
            'success' => $success,
            'message' => $message,
            'errors'  => $errors
        );
        $this->json($result);
    }
}