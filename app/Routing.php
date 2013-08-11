<?php

class Routing {

    private $config;
    private $user;

    public function  __construct($user) {

        $this->config = Config::from('routing');
        $this->analyzeConfig();
        $this->user = $user;
    }

    /**
     * Analyzes the routing configuration and discovers any
     * ambiguous or otherwise problematic configurations,
     * throwing an exception if one is found.
     *
     * @throws Exception
     */
    private function analyzeConfig() {

        $uniquePaths = array();

        foreach ($this->config->getUnion('routes') as $path => $config) {

            $paramNames = array();
            $pathArray = $this->getPathArray($path);
            $uniqueName = array();

            foreach ($pathArray as $part) {

                if ($param = $this->isParameter($part)) {

                    if (in_array($param['name'], $paramNames)) {
                        throw new Exception('Duplicate parameter name found in ' . $path);
                    }

                    $paramNames[] = $param['name'];
                    $uniqueName[] = ':param';
                } else {
                    $uniqueName[] = $part;
                }
            }

            $uniqueName = implode('/', $uniqueName);
            if (isset($uniquePaths[$uniqueName])) {
                $existing = $uniquePaths[$uniqueName];
                throw new Exception("Ambiguous or duplicate paths discovered: $existing and $path");
            }
        }
    }

    private function getPriority($path, $url) {

        $pathArray = $this->getPathArray($path);
        $urlArray = $this->getPathArray($url);
        $priority = 0;

        $hasParams = false;

        for ($i = 0; $i < count($pathArray); ++$i) {

            if ($param = $this->isParameter($pathArray[$i])) {

                if ($param['required']) {
                    if (isset($urlArray[$i])) {
                        $priority += 5;
                    } else {
                        return false;
                    }
                } else {
                    $priority += isset($urlArray[$i]) ? 5 : 2;
                }

            } else if (isset($urlArray[$i]) && $pathArray[$i] === $urlArray[$i]) {

                $priority += 10;
            } else {

                return false;
            }
        }

        if (!$hasParams && count($urlArray) === count($pathArray)) {
            return true;
        }

        return $priority;
    }

    public function route($url) {

        $url = preg_replace('/\\+|\/+/', '/', $url);
        $url = self::prepPath($url);

        $highestPriority = 0;
        $bestPath = null;

        foreach ($this->config->getUnion('routes') as $path => $config) {

            $priority = $this->getPriority($path, $url);

            if ($priority === true) {

                $bestPath = $path;
                break;

            } else if ($priority !== false && $priority > $highestPriority) {

                $highestPriority = $priority;
                $bestPath = $path;
            }
        }

        $this->dispatch($bestPath, $url);
    }

    private function dispatch($path, $url) {

        if ($path === null) {
            header("Refresh:0; url=/", true, 404);
        }

        $securityConfig = array();
        foreach($this->config->getUnion('security') as $regex => $config) {
            if ($regex == "all") {
                foreach($config as $name => $value) {
                    if (!isset($securityConfig[$name])) {
                        $securityConfig[$name] = $value;
                    }
                }
            } else {
                if (preg_match($regex, $path)) {
                    foreach($config as $name => $value) {
                        $securityConfig[$name] = $value;
                    }
                }
            }
        }

        $routeConfig = $this->config->get('routes.'.$path);
        $params = $this->extractParams($path, $url);

        $security = new Security($securityConfig);
        $authorized = $security->checkAuthentication($this->user);

        if (!$authorized) {
            header("Refresh:0; url=/");
        } else {
            $route = new Route($routeConfig);
            $route->dispatch($this->user, $params);
        }
    }

    private function prepPath($path) {

        //trim path of /
        $path = preg_replace('/^\/+|\/+$/', '', $path);

        return $path;
    }

    private function getPathArray($pathInfo) {

        $pathInfo = $this->prepPath($pathInfo);

        return explode('/', $pathInfo);
    }

    private function extractParams($path, $url) {

        $pathArray = $this->getPathArray($path);
        $urlArray = $this->getPathArray($url);
        $params = array();

        foreach ($pathArray as $index => $part) {

            if ($param = $this->isParameter($part)) {

                $params[$param['name']] = $urlArray[$index];
            }
        }

        return $params;
    }

    private function isParameter($part) {

        $result = preg_match('/^:([A-Za-z-_]+)(\??)$/', $part, $parameter);

        if ($result === 0) {
            return false;
        }

        $parameter = array(
            'name'     => $parameter[1],
            'required' => empty($parameter[2]));

        return $parameter;
    }

}