<?php

class Config implements Iterator {

    /** @var array $config */
    private $config;

    private static $cache;
    private $env;

    private $allKeys;

    private $separator;

    /**
     * @param string $fileName
     *
     * @return Config
     */
    public static function from($fileName) {

        $filePath = __DIR__.'/../config/'.$fileName.'.json';

        if (isset(self::$cache[$fileName])) {
            return self::$cache[$fileName];
        }

        self::$cache[$fileName] = new Config($filePath, App::getEnv());
        return self::$cache[$fileName];
    }

    private function __construct($path, $env) {

        $this->config = $this->loadFile($path);

        if (isset($this->config['separator']) && is_string($this->config['separator'])) {
            $this->separator = $this->config['separator'];
        } else {
            $this->separator = '.';
        }

        if ($env === null) {
            $this->env = self::from('config', 'all')->get('env');
        } else {
            $this->env = $env;
        }

        $this->allKeys = array();
        if (isset($this->config[$this->env])) {
            $this->allKeys = array_merge($this->allKeys, array_keys($this->config[$this->env]));
        }

        if (isset($this->config['all'])) {
            $this->allKeys = array_merge($this->allKeys, array_keys($this->config['all']));
        }
    }

    private function loadFile($path) {

        $contents = file_get_contents($path);
        return json_decode($contents, true);
    }

    public function getByPath($path) {

        $parts = explode($this->separator, $path);

        $branch = $this->config;

        foreach($parts as $part) {

            if (isset($branch[$part])) {
                $branch = $branch[$part];
            } else {
                $branch = null;
                break;
            }
        }

        return $branch;
    }

    private function namespacePath($path, $ns) {

        return implode($this->separator, array($ns, $path));
    }

    public function getUnion($path) {

        $envPath = $this->namespacePath($path, $this->env);
        $defPath = $this->namespacePath($path, 'all');

        $envValue = $this->getByPath($envPath);
        $defValue = $this->getByPath($defPath);

        if (is_null($envValue)) {
            $envValue = array();
        }
        if (is_null($defValue)) {
            $defValue = array();
        }

        if (!is_array($defValue) || !is_array($envValue)) {
            throw new Exception('Both the default and current environment values must be arrays or objects for getUnion().');
        }

        //todo support for non associative arrays -- simply array_merge them.
        foreach($envValue as $key => $value) {
            $defValue[$key] = $value;
        }

        return $defValue;
    }

    public function get($path = null) {

        $envPath = $this->namespacePath($path, $this->env);
        $defPath = $this->namespacePath($path, 'all');

        $envValue = $this->getByPath($envPath);
        $defValue = $this->getByPath($defPath);

        return $envValue ?: $defValue;
    }

    public function rewind() {
        reset($this->allKeys);
    }

    public function current() {
        return $this->get(current($this->allKeys));
    }

    public function key() {
        return current($this->allKeys);
    }

    public function next() {
        next($this->allKeys);
    }

    public function valid() {
        return $this->current() !== null;
    }
}
