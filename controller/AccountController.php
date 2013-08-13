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
            ->where("customer_id = $customerId")
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
        $keystring = Config::from('config')->get('keystring');
        $customerId = $this->getUser()->get('customer_id');

        $sql = "UPDATE `account` SET
                `account_name` = AES_ENCRYPT(:name, :keystring),
                `aws_key` = AES_ENCRYPT(:aws_key, :keystring),
                `secret_key` = AES_ENCRYPT(:secret_key, :keystring)
                WHERE `account_id` = :id;
                and `customer_id` = :customer_id";

        $db->begin();
        $stmt = $db->getConnection()->prepare($sql);

        $params = Utils::stripNotIn($request->getParams(),
            array('id', 'name', 'aws_key', 'secret_key'));
        $params['keystring'] = $keystring;
        $params['customer_id'] = $customerId;

        if (!$stmt->execute($params)) {
            $result = array(
                'success' => false,
                'message' => implode(': ', $stmt->errorInfo())
            );
        } else if ($stmt->rowCount() === 0 &&
            0 == count($db->fetchAll(
                'select * from account where account_id = ?', array($id)))) {

            $result = array(
                'success' => false,
                'message' => 'Account not found.'
            );
        } else {
            $result = array(
                'success' => true,
                'message' => $params['name'].' saved.'
            );
        }

        if ($result['success'] === true) {
            $db->commit();
        } else {
            $db->rollBack();
        }

        $this->json($result);
    }
}