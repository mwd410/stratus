<?php

class AnalysisController extends Controller {

    public function overviewAction(Request $request) {

        $customerId = $this->getUser()->get('customer_id');

        $totals = Query::create(Query::SELECT)
            ->column('sum(vh.total_qty) as total_quantity')
            ->column('a.account_id')
            ->from('volume_history vh')
            ->join('account a on a.account_id = vh.account_id')
            ->join('customer c on a.customer_id = c.id')
            ->where('c.id = ?', $customerId)
            ->where('vh.history_date = curdate()')
            ->execute();

        $this->render('analysisOverview', array('totals' => $totals));
    }
}