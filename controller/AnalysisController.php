<?php

class AnalysisController extends Controller {

    public function overviewAction(Request $request) {

        $customerId = $this->getUser()->get('customer_id');

        $accounts = Account::getBasicSelect()
            ->where('customer_id = ?', $customerId)
            ->where('deleted = 0')
            ->execute();

        $tableData = array();
        foreach($accounts as $account) {

            $tableRow = array(
                'name' => $account['name'],
                'id' => $account['id']
            );

            $today = Query::create(Query::SELECT)
                ->column('sum(vh.total_qty) as total_quantity')
                ->from('volume_history vh')
                ->leftJoin('account a on vh.account_id = a.account_id')
                ->leftJoin('customer c on c.id = a.customer_id')
                ->where('vh.history_date = date(now())')
                ->where('c.id = ?', $customerId)
                ->where('a.account_id = ?', $account['id'])
                ->execute();
        }

        //dev data
        $totals = array(
            array(
                'id'   => '123',
                'name' => 'test',
                'cost' => 1,
                'daily' => 3,
                'weekly' => 4,
                'monthly' => 5
            )
        );

        $this->render('analysisOverview', array('totals' => $totals));
    }
}