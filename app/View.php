<?php

/**
 * Embed all the appropriate css stylesheets in this location.
 */
function includeStyleSheets() {

    View::includeStyleSheets();
}

/**
 * Embed the appropriate <script> tags in this location.
 */
function includeJavaScripts() {

    View::includeJavaScripts();
}

/**
 * The {@link $view} will have this view's partials passed to it.
 * The <code>$view</code> can be referred to as the 'parent' view.
 * @param string $view The file name with or without the
 * extension (which will default to .php without)
 */
function extendView($view) {

    View::extendView($view);
}

function insertPartial($name, $default = '') {

    View::insertPartial($name, $default);
}

function setPartial($name, $value) {

    View::setPartial($name, $value);
}

function beginPartial($name) {

    View::beginPartial($name);
}

function endPartial() {

    View::endPartial();
}

function forIn($name, $items) {

}

function importPartial($name, $args = array()) {

    View::importPartial($name, $args);
}

class View {

    private $fileExtension;
    private $fileName;
    private $filePath;
    private $partials;
    private $parentViewName;
    private $currentPartial;
    /** @var View $child */
    private $child;
    private $result;

    private static $views = array();
    /** @var  Config $config */
    private static $config;

    /**
     * Determines the view that called one of the templating functions
     * by analyzing the call stack.
     *
     * @param string $fnName The templating function name to look for.
     *                       If not specified, it will return the most recent call to one of
     *                       the utility functions.
     *
     * @return View
     * @throws Exception
     */
    private static function getCallingView($fnName = null) {

        $stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        $viewName = null;

        foreach ($stack as $context) {

            if (!isset($context['type']) && !isset($context['class'])) {

                if ($fnName === null || $context['function']) {

                    $file = $context['file'];
                    $fileInfo = pathinfo($file);

                    $viewName = $fileInfo['filename'];
                }
            }
            if ($viewName !== null) {
                break;
            }
        }

        if ($viewName === null) {
            throw new Exception('Could not find the calling view.');
        }

        return self::$views[$viewName];
    }

    public static function getConfig() {

        if (!isset(self::$config)) {
            self::$config = Config::from('view');
        }
        return self::$config;
    }

    public static function includeStyleSheets() {

        $config = self::getConfig();

        $cssSheets = $config->getUnion('css');

        foreach($cssSheets as $cssFile) {

            if (is_string($cssFile)) {
                $cssFile = array('href' => $cssFile);
            }

            if (!isset($cssFile['href'])) {
                throw new Exception('CSS stylesheet configuration must specify an href: '.json_encode($cssFile));
            }

            Utils::applyIf($cssFile, array(
              'rel'  => 'stylesheet',
              'type' => 'text/css'));

            echo Utils::buildDom('link', $cssFile);
        }
    }

    public static function includeJavaScripts() {

        $config = self::getConfig();

        $javaScripts = $config->getUnion('js');

        foreach ($javaScripts as $jsFile) {
            echo '<script src="' . $jsFile . '"></script>';
        }
    }

    public static function extendView($name) {

        $childView = self::getCallingView();

        if ($childView->getParentName() !== null) {
            throw new Exception('A view can only extend one other view.');
        }
        $childView->setParentName($name);
    }

    public static function setPartial($name, $value) {

        self::getCallingView('setPartial')->partials[$name] = $value;
    }

    public static function insertPartial($name, $default = '') {

        $view = self::getCallingView();
        $partial = $view->getPartial($name) ? : $default;

        echo $partial;
    }

    public static function beginPartial($name) {

        $view = self::getCallingView();
        $view->setCurrentPartial($name);

        ob_start();
    }

    public static function endPartial() {

        $view = self::getCallingView();
        $name = $view->getCurrentPartial();

        $partial = ob_get_clean();
        $view->partials[$name] = $partial;

        $view->currentPartial = null;
    }

    public static function importPartial($name, $args = array()) {

        if ($args === true) {
            $view = self::getCallingView();
        }

        $partial = new View($name);
        $partial->render($args);

        echo $partial->result;
    }

    /**------------------------------
    | Instance Methods               |
    -------------------------------*/

    /**
     * @param      $fileName
     * @param null $child
     */
    public function __construct($fileName, $child = null) {


        $this->fileExtension = 'php';
        $this->readFileInformation($fileName);
        $this->result = '';
        $this->child = $child;

        self::$views[$this->getViewName()] = $this;
    }

    public function render($args = array()) {

        if ($this->child !== null) {
            $this->result .= $this->child->result;
        }

        ob_start();

        if ($this->getFileExtension() === 'php') {

            extract($args, EXTR_SKIP);
            require($this->getFilePath());

        } else {

            $file = file_get_contents($this->getFilePath());
            echo $file;
        }
        $this->result = ob_get_clean();
        $this->passToParent($args);
    }

    private function passToParent($args = array()) {

        if ($this->getParentName() === null) {
            return;
        }

        $parent = new View($this->getParentName(), $this);
        $parent->render($args);
    }

    private function readFileInformation($fileName) {

        $filePath = dirname(dirname(__FILE__)) . '/view/' . $fileName;

        $pathInfo = pathinfo($filePath);

        if (!empty($pathInfo['extension'])) {
            $this->setFileExtension($pathInfo['extension']);
        }
        $this->setFileName($pathInfo['filename']);

        $this->setFilePath($pathInfo['dirname'] . '/' . $this->fileName . '.' . $this->fileExtension);
    }

    private function setFilePath($filePath) {

        $this->filePath = $filePath;
    }

    private function setFileName($fileName) {

        $this->fileName = $fileName;
    }

    private function setFileExtension($ext) {

        $ext = strtolower($ext);

        switch ($ext) {
            case 'php':
            case 'html':
            case 'htm':
                $this->fileExtension = $ext;
                break;
            default:
                throw new Exception("Unsupported file extension '$ext'.");
        }
    }

    public function getViewName() {

        return $this->getFileName();
    }

    public function getFilePath() {

        return $this->filePath;
    }

    public function getFileName() {

        return $this->fileName;
    }

    public function getFileExtension() {

        return $this->fileExtension;
    }

    private function setParentName($name) {

        $this->parentViewName = $name;
    }

    public function getParentName() {

        return $this->parentViewName;
    }

    public function setCurrentPartial($partial) {

        if ($this->currentPartial !== null) {
            throw new Exception('You cannot nest partial values.');
        }

        $this->currentPartial = $partial;
    }

    public function getCurrentPartial() {

        return $this->currentPartial;
    }

    public function hasChild() {

        return $this->child !== null;
    }

    public function hasPartial($name) {

        return isset($this->partials[$name]);
    }

    public function getPartial($name) {

        $partial = $this->hasPartial($name) ? $this->partials[$name] : null;

        if ($this->hasChild()) {
            $childPartial = $this->child->getPartial($name);
        } else {
            $childPartial = null;
        }

        return $childPartial ? : $partial;
    }

    public function getResult() {

        $view = $this;
        while ($parentName = $view->getParentName()) {

            $view = self::$views[$parentName];
        }

        return $view->result;
    }
}