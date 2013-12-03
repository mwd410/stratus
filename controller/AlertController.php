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

    public function updateAction(Request $request) {


    }
}
