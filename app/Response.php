<?php
/**
 * Created by JetBrains PhpStorm.
 * User: matt.deady
 * Date: 7/30/13
 * Time: 9:20 PM
 * To change this template use File | Settings | File Templates.
 */

class Response {

    private $hasSent;
    private $content;
    private $contentType;
    private $code;
    private $length;

    const CONTENT_TYPE_JSON = 'application/json';
    const CONTENT_TYPE_HTML = 'text/html';

    public function __construct() {
        $this->hasSent = false;
        $this->content = null;
        $this->code = null;
        $this->contentType = null;
    }

    public function send() {

        if ($this->hasSent) {
            throw new Exception('The response has already been sent. You cannot send it again.');
        }

        if ($this->code === null) {
            $this->setCode(200);
        }
        //http_response_code($this->code);
        if ($this->contentType === null) {

        }

        header(sprintf('HTTP/1.0 %s', $this->code));
        echo $this->content;
    }

    public function setContent($text, $type = null) {

        if (!is_string($text)) {
            throw new Exception('Invalid parameter type in setContent. $text must be string.');
        }

        if ($type !== null) {
            $this->setContentType($type);
        }

        $this->length = strlen($text);
        $this->content = $text;
    }

    public function setCode($code) {

        if (!is_int($code)) {
            throw new Exception('$code must be an int.');
        }
        $this->code = $code;
    }

    public function setContentType($type) {

        if (!is_string($type)) {
            throw new Exception('$type must be an int.');
        }
        $this->contentType = $type;
    }
}