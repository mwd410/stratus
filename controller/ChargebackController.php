<?php
/**
 * Created by PhpStorm.
 * User: matt.deady
 * Date: 12/10/13
 * Time: 8:44 PM
 */

class ChargebackController extends Controller {

    public function infoAction(Request $request) {

        $customerId = $this->getUser()->get('customer_id');

        $accountProducts = Query::select('billing_history_v')
            ->isDistinct()
            ->column('account_id')
            ->column('account_name')
            ->column('service_provider_id')
            ->column('service_provider_name')
            ->column('service_product_id as service_provider_product_id')
            ->column('service_provider_product_name')
            ->where('customer_id = ' . $customerId)
            ->execute();

        $builder = new ResponseBuilder();
        $builder->setData(array(
            'accountProducts' => $accountProducts
        ));

        $this->json($builder->getResponse());
    }

    public function indexAction(Request $request) {

        $customerId = $this->getUser()->get('customer_id');

        $builder = new ResponseBuilder();
        $builder->setData(array(
            'stakeholders' => Query::select('stakeholder')
                    ->where('customer_id = ' . $customerId)
                    ->execute(),
            'chargeback'   => Query::select('chargeback_unit_v')
                    ->where('customer_id = ' . $customerId)
                    ->execute(),
            'accounts'     => Query::select('account a')
                    ->column('a.id')
                    ->column('a.name')
                    ->column('a.service_provider_id')
                    ->column('sp.name as service_provider_name')
                    ->join('service_provider_v sp')
                    ->where('customer_id = ' . $customerId)
                    ->execute()
        ));

        $this->json($builder->getResponse());
    }

    public function createStakeholderAction(Request $request) {

        $name = $request->getParam('name');
        $title = $request->getParam('title');
        $email = $request->getParam('email');

        $customerId = $this->getUser()->get('customer_id');
    }

    public function assignAction(Request $request) {

        $accountId = $request->getParam('account_id');
        $stakeholderId = $request->getParam('stakeholder_id');
        $customerId = $this->getUser()->get('customer_id');
        $builder = new ResponseBuilder();

        $stakeholder = Query::select('stakeholder')
            ->column('customer_id')
            ->where('id = ?', $stakeholderId)
            ->fetchOne();

        $account = Query::select('account')
            ->column('customer_id')
            ->where('id = ?', $accountId)
            ->fetchOne();

        if (!$stakeholder || !$account) {
            $builder->addError('not-found', 'Sorry, we could not find the specified account or stakeholder.');
            $this->json($builder->getResponse());
            return;
        }

        if ($customerId != $stakeholder['customer_id']
            || $customerId != $account['customer_id']) {

            $builder->addError('forbidden', 'Sorry, you do not have permission to assign this chargeback unit to this stakeholder.');
            $this->json($builder->getResponse());
            return;
        }

        $success = Query::executeStmt("REPLACE INTO chargeback_unit
            (stakeholder_id, account_id)
            VALUES (?", $stakeholderId, ', ?)', $accountId);

        $builder->setSuccess($success);
        $this->json($builder->getResponse());
    }
} 
