<?php
/**
 * Created by JetBrains PhpStorm.
 * User: matt.deady
 * Date: 7/30/13
 * Time: 9:15 PM
 * To change this template use File | Settings | File Templates.
 */

abstract class Controller {

    /** @var  Response $response */
    protected $response;
    protected $request;
    protected $user;

    public function __construct($user, $request, $response) {

        $this->user = $user;
        $this->request = $request;
        $this->response = $response;
    }

    protected function json($data) {

        $this->getResponse()->setContent(json_encode($data), Response::CONTENT_TYPE_JSON);
    }

    protected function text($text) {

        $this->getResponse()->setContent($text, Response::CONTENT_TYPE_HTML);
    }

    /**
     * @return User
     */
    public function getUser() {
        return $this->user;
    }

    public function getRequest() {

        return $this->request;
    }

    /**
     * @return Response
     */
    public function getResponse() {

        return $this->response;
    }

    public function render($viewFile, $args = array()) {

        $args['user'] = $this->getUser();

        $view = new View($viewFile);
        $view->render($args);

        $this->text($view->getResult());
    }

    public function forward($to) {

        header("Refresh:0; url=$to", false);
    }
}