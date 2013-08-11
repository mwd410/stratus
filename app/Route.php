<?php

class Route {

    private $pathInfo;
    private $params;
    private $config;

    public function __construct($config) {

        if (is_array($config)) {
            $this->config = $config;
        } else if (is_string($config)) {
            $conAct = explode('.', $config);
            $this->config = array(
                'controller' => $conAct[0],
                'action'     => $conAct[1]
            );
        }

        if (!isset($this->config['controller'])
            || !isset($this->config['action'])) {

            throw new Exception('Improperly configured route.');
        }
    }

    public function get($name) {

        return $this->config[$name];
    }

    public function set($name, $value) {

        $this->config[$name] = $value;
        return $this;
    }

    public function dispatch($user, $params) {

        $controller = $this->get('controller').'Controller';
        $actionFn = $this->get('action').'Action';

        $request = new Request($params);
        $response = new Response();

        $ctrl = new $controller($user, $request, $response);
        $ctrl->$actionFn($request);

        $ctrl->getResponse()->send();

        return true;
    }

    public function forward($path) {

        $pathArray = explode('/', $path);
        $staticCount = $this->getStaticCount();
        $static = array_slice($pathArray, 0, $staticCount);

        if ($staticCount == 2) {
            $controller = $static[0];
            $action = $static[1];
        } else {
            $controller = $this->config['controller'];
            $action = $this->config['action'];
        }

        $params = $this->getParams($pathArray);

        $request = new Request($params);
        $response = new Response();

        $ControllerClass = $controller.'Controller';

        /** @var Controller $ctrl */
        $ctrl = new $ControllerClass($request, $response);
        $actionFn = $action.'Action';
        $ctrl->$actionFn($request);

        $ctrl->getResponse()->send();
    }

    private function getParams($pathArray) {

        if (count($this->params) === 0) {
            return array();
        }

        $paramValues = array_slice($pathArray, $this->getStaticCount());
        $paramNames = array_keys($this->params);

        foreach ($paramValues as $index => $value) {
            if (empty($value)) {
                unset($paramValues[$index]);
            }
        }

        $paramValues = array_unique($paramValues);

        if (count($paramValues) > count($paramNames)) {

            $paramValues = array_slice($paramValues, 0, count($paramNames));

        } else if (count($paramNames) > count($paramValues)) {

            for ($i = count($paramValues); $i < count($paramNames); ++$i) {
                $paramValues[] = $this->getDefault($paramNames[$i]);
            }
        }

        $params = array_combine($paramNames, $paramValues);

        return $params;
    }

    public function getPathInfo() {
        return $this->pathInfo;
    }

    private function getDefault($name) {

        if (!isset($this->config['defaults'])) {
            return null;
        }

        $val = null;
        if (isset($this->config['defaults'][$name])) {

            $val = $this->config['defaults'][$name];
        }

        return $val;
    }


}