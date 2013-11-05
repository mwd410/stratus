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
            //->column('sp.name as service_provider_name')
            //->join('service_provider sp on sp.id = service_provider_id')
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
            'masterAccount' => empty($masterAccount) ? array(
                    'account_id' => null,
                    'billing_bucket' => ''
                ) : $masterAccount[0]
        );
        $this->json($result);
    }

    public function addAction(Request $request) {

        $account = $request->getParam('account');
        $master = $request->getParam('master');

        $db = Database::getInstance();
        $customerId = $this->getUser()->get('customer_id');

        $params = Utils::stripNotIn($account,
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

            Account::saveMaster($master, $customerId);

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

        $account = $request->getParam('account');
        $master  = $request->getParam('master');

        $id = $account['id'];
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

                $params = $account;
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

                    Account::saveMaster($master, $customerId);

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

    public function deleteAction(Request $request) {

        $customerId = $this->getUser()->get('customer_id');

        Account::delete($request->getParam('id'), $customerId);
        $result = array(
            'success' => true,
            'message' => 'Account deleted.'
        );
        $this->json($result);
    }
}
