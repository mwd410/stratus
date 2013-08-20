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

        $totals = $this->getOverviewData();

        $this->render('analysisOverview', array('totals' => $totals));
    }

    private function getOverviewData() {

        $db = Database::getInstance();
        $customerId = $this->getUser()->get('customer_id');
        $keystring = Config::from('config')->get('keystring');

        //get the accounts for the user
        $accounts = $db->fetchAll("SELECT account_id, AES_DECRYPT(account_name,'$keystring') as account_name FROM account WHERE customer_id={$customerId} AND deleted=0");

        $list = array();
        $i = 0;
        foreach ($accounts as $account) {
            $list[$i]['name'] = $account['account_name'];
            $list[$i]['id'] = $account['account_id'];

            /*if(!isset($_SESSION['account_name']) && !isset($_SESSION['account_id'])){ //to have a default selected account, if needed just uncomment
                $_SESSION['account_name'] = $account['account_name'];
                $_SESSION['account_id'] = $account['account_id'];
            }*/
            //queries for the %, change as needed.

            //daily
            $a = $db->fetchAll('select sum(t1.total_qty) as total_qty from volume_history as t1 left join account on t1.account_id = account.account_id left join customer on customer.id = account.customer_id where t1.history_date = date(now()) and customer.id=' . $customerId . " AND account.account_id = {$account['account_id']}");

            $b = $db->fetchAll('select sum(t1.total_qty) as total_qty from volume_history as t1 left join account on t1.account_id = account.account_id left join customer on customer.id = account.customer_id where t1.history_date = last_day(now() - interval 1 month) and customer.id=' . $customerId . " AND account.account_id = {$account['account_id']}");

            $num1 = $a[0];
            $num2 = $b[0];

            if ($num1['total_qty'] > 0) { //to prevent division by 0
                $list[$i]['daily']['value'] = round((($num1['total_qty'] - $num2['total_qty']) / $num1['total_qty']) * 100, 2);
                $value = (string)$list[$i]['daily']['value'];

                if (strpos($value, "-") === false) {
                    $list[$i]['daily']['gainloss'] = "loss";
                    $list[$i]['daily']['value'] = floatval($value);
                } else {
                    $list[$i]['daily']['gainloss'] = "gain";
                    $list[$i]['daily']['value'] = floatval($value);
                }

            } else {
                $list[$i]['daily']['value'] = 0;
                $list[$i]['daily']['gainloss'] = "loss";
            }
            //end of daily
            //================================================================================
            //weekly
            $a = $db->fetchAll('select sum((t1.total_size * t1.monthly_rate) / 30) as total_qty from volume_history as t1 left join account on t1.account_id = account.account_id left join customer on customer.id = account.customer_id where t1.history_date = date(now()) and customer.id=' . $customerId . " AND account.account_id = {$account['account_id']}");

            $b = $db->fetchAll('select sum((t1.total_size * t1.monthly_rate) / 30) as total_qty from volume_history as t1 left join account on t1.account_id = account.account_id left join customer on customer.id = account.customer_id where t1.history_date = subdate(current_date, 1) and customer.id=' . $customerId . " AND account.account_id = {$account['account_id']}");

            $num1 = $a[0];
            $num2 = $b[0];

            if ($num1['total_qty'] > 0) { //to prevent division by 0
                $list[$i]['weekly']['value'] = round((($num1['total_qty'] - $num2['total_qty']) / $num1['total_qty']) * 100, 2);
                $value = (string)$list[$i]['weekly']['value'];

                if (strpos($value, "-") === false) {
                    $list[$i]['weekly']['gainloss'] = "loss";
                    $list[$i]['weekly']['value'] = floatval($value);
                } else {
                    $list[$i]['weekly']['gainloss'] = "gain";
                    $list[$i]['weekly']['value'] = floatval($value);
                }

            } else {
                $list[$i]['weekly']['value'] = 0;
                $list[$i]['weekly']['gainloss'] = "loss";
            }

            //monthly
            $a = $db->fetchAll('select sum((t1.total_size * t1.monthly_rate) / 30) as total_qty from volume_history as t1 left join account on t1.account_id = account.account_id left join customer on customer.id = account.customer_id where t1.history_date = date(now()) and customer.id=' . $customerId . " AND account.account_id = {$account['account_id']}");

            $b = $db->fetchAll('select sum((t1.total_size * t1.monthly_rate) / 30) as total_qty from volume_history as t1 left join account on t1.account_id = account.account_id left join customer on customer.id = account.customer_id where t1.history_date = date_sub(curdate(), interval 1 month) and customer.id=' . $customerId . " AND account.account_id = {$account['account_id']}");

            $num1 = $a[0];
            $num2 = $b[0];

            if ($num1['total_qty'] > 0) { //to prevent division by 0
                $list[$i]['monthly']['value'] = round((($num1['total_qty'] - $num2['total_qty']) / $num1['total_qty']) * 100, 2);
                $value = (string)$list[$i]['monthly']['value'];

                if (strpos($value, "-") === false) {
                    $list[$i]['monthly']['gainloss'] = "loss";
                    $list[$i]['monthly']['value'] = floatval($value);
                } else {
                    $list[$i]['monthly']['gainloss'] = "gain";
                    $list[$i]['monthly']['value'] = floatval($value);
                }

            } else {
                $list[$i]['monthly']['value'] = 0;
                $list[$i]['monthly']['gainloss'] = "loss";
            }

            //for now i'll just copy the weekly and monthly values from the daily value.


            //cost
            $query1 = $db->fetchAll('select sum(t1.total_qty) as total_qty from volume_history as t1 left join account on t1.account_id = account.account_id left join customer on customer.id = account.customer_id where t1.history_date = date(now()) and customer.id=' . $customerId . " AND account.account_id = {$account['account_id']}");
            $obj = $query1[0];
            if ($obj['total_qty'] == "") {
                $cost = 0;
            } else {
                $cost = $obj['total_qty'];
            }
            $list[$i]['cost'] = "$" . $cost; //just static. to be replaced with the value from an actual query.

            $i++;
        }

        return $list;
    }
}