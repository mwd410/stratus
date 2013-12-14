<?php
/**
 * Created by PhpStorm.
 * User: matt.deady
 * Date: 11/16/13
 * Time: 12:40 PM
 */

class AlertController extends Controller {

    public function infoAction(Request $request) {

        $builder = new ResponseBuilder();
        $data = array(
            'comparisonTypes'  => Utils::mapValues(Query::selectAllFrom('comparison_type')),
            'calculationTypes' => Utils::mapValues(Query::selectAllFrom('calculation_type')),
            'timeFrames'       => Utils::mapValues(Query::selectAllFrom('time_frame')),
            'valueTypes'       => Utils::mapValues(Query::selectAllFrom('value_type'))
        );

        $builder->setData($data);

        $this->json($builder->getResponse());
    }

    public function indexAction(Request $request) {

        $userId = $this->getUser()->get('id');

        $alerts = Query::create(Query::SELECT)
            ->column('*')
            ->from('alert')
            ->where('user_id = ' . $userId)
            ->execute();

        $this->json(array(
            'alerts' => $alerts
        ));
    }

    public function deleteAction(Request $request) {

        $alertId = $request->getParam('id');
        $userId = $this->getUser()->get('id');

        $alert = Query::create(Query::SELECT)
            ->column('user_id')
            ->from('alert')
            ->where('id = ?', $alertId)
            ->execute();

        if (count($alert) === 0) {
            $this->json(array(
                'success' => false,
                'message' => 'Alert not found.'
            ));

            return;
        }

        $alert = $alert[0];

        if ($userId != $alert['user_id']) {
            $this->json(array(
                'success' => false,
                'message' => 'You do not have permission to delete this alert.'
            ));

            return;
        }

        $success = Query::delete('alert')
            ->where('id = ?', $alertId)
            ->execute();

        $this->json(array(
            'success' => $success
        ));
    }

    public function createAction(Request $request) {

        $builder = new ResponseBuilder();

        $params = $request->getParams();
        $allowed = array_diff(Alert::$columns, array('id'));
        $params = Utils::stripNotIn($params, $allowed);
        $hasAll = Utils::requireExactly($params, Alert::$columns);

        if ($hasAll !== true) {

            $builder->addError('field-missing',
                'You are missing the following fields: '
                . implode(', ', $hasAll));
        }

        $params['user_id'] = $this->getUser()->get('id');

        Query::begin();

        $columns = array();
        $queryParams = array();

        foreach($params as $key => $value) {

            $columns[] = $key;
            $queryParams[] = $value;
        }
        $values = array_fill(0, count($columns), '?');

        $columnList = implode(',', $columns);
        $valueList = implode(',', $values);

        $sql = "insert into alert ($columnList) values ($valueList)";

        if (!Query::executeStmt($sql, $queryParams)) {

            Query::rollback();

            $builder->addError('internal', 'There was an internal error');
            throw new Exception(implode(' : ', Query::stmt()->errorInfo()));
        }

        $lastInsertId = Query::lastInsertId('alert');

        Query::commit();

        $builder->setData(Query::select('alert')
            ->where('id = ?', $lastInsertId)
            ->fetchOne());

        $this->json($builder->getResponse());
    }

    public function updateAction(Request $request) {

        $params = $request->getParams();
        $allowed = array_diff(Alert::$columns, array('user_id'));
        $params = Utils::stripNotIn($params, $allowed);
        $builder = new ResponseBuilder();

        $id = $params['id'];
        $userId = $this->getUser()->get('id');

        $existingAlert = Query::select('alert')
            ->column('*')
            ->where('id = ?', $id)
            ->fetchOne();

        if ($existingAlert === null) {
            $builder->addError('id', "Invalid alert id '$id'");
            $this->json($builder->getResponse());
            return;
        }

        if ($userId != $existingAlert['user_id']) {
            $builder->addError('You do not have permission to edit this alert');
            $this->json($builder->getResponse());
            return;
        }

        foreach($existingAlert as $key => $value) {

            if (!isset($params[$key])) {
                continue;
            }

            if ($params[$key] === true) {
                $params[$key] = '1';
            } else if ($params[$key] === false) {
                $params[$key] = '0';
            }
            // check isset to skip user_id, which is not a required param.
            if ($value == $params[$key]) {

                unset($params[$key]);
            }
        }

        if (empty($params)) {
            $builder->addWarning('No values changed.');
        } else {

            $updateQuery = Query::update('alert')
                ->setAll($params)
                ->where('id = ?', $id);

            if (!$updateQuery->execute()) {

                $error = $updateQuery->getStatement()->errorInfo();
                $builder->addError(implode(': ', $error));
            } else {

                $builder->setData(Query::select('alert')
                    ->column('*')
                    ->where('id = ?', $id)
                    ->fetchOne());
            }
        }

        $this->json($builder->getResponse());
    }
}
