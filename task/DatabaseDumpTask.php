<?php
/**
 * Created by JetBrains PhpStorm.
 * User: matt.deady
 * Date: 7/31/13
 * Time: 6:29 PM
 * To change this template use File | Settings | File Templates.
 */

class DatabaseDumpTask extends Task {

    protected function setup() {

        $this->addParam(
            array(
                'name'          => 'f',
                'description'   => 'The output filename inside <project_dir>/db/backup',
                'required'      => true,
                'valueOptional' => true
            )
        );
    }

    protected function run($cliArgs, $params) {

    }

}