<?php

class AnalysisController extends Controller {

    public function overviewAction(Request $request) {

        $this->render('analysisOverview', array('totals' => $totals));
    }

}