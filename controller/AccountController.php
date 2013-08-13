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

        $this->json($accounts);
    }

    public function addAction(Request $request) {

        $db = Database::getInstance();
        $customerId = $this->getUser()->get('customer_id');
        $keystring = Config::from('config')->get('keystring');

        $sql = "INSERT INTO account
                (customer_id, account_name, aws_key, secret_key, deleted)
                VALUES
                (:customer_id,
                AES_ENCRYPT(:name, '$keystring'),
                AES_ENCRYPT(:aws_key, '$keystring'),
                AES_ENCRYPT(:secret_key, '$keystring'),
                0)";

        $params = Utils::stripNotIn($request->getParams(),
            array('name', 'aws_key', 'secret_key'));
        $params['customer_id'] = $customerId;

        $db->begin();

        $stmt = $db->getConnection()->prepare($sql);
        if (!$stmt->execute($params)) {
            $db->rollback();
            $result = array(
                'success' => false,
                'message' => implode(': ', $stmt->errorInfo())
            );
        } else {
            $id = $db->getConnection()->lastInsertId();
            unset($params['customer_id']);
            $params['id'] = $id;
            $db->commit();

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

        if (empty($existing)) {

            $message = 'Account not found';
        } else {

            $existing = $existing[0];

            if ($existing['customer_id'] != $customerId) {

                $message = 'Sorry, you do not have access to this account.';
            } else {

                $params = $request->getParams();
                $params['customer_id'] = $customerId;

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
        $result = array(
            'success' => $success,
            'message' => $message
        );
        $this->json($result);
    }
}