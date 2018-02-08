<?php

namespace App\Core;

class Application
{
    protected $services = [];
    protected static $instance = NULL;
    protected $routes = [];
    protected $config = [];

    protected function __construct()
    {
        $this->config = include __DIR__ . '/../config.php';
        $config = array(
            'driver'    => 'mysql',
            'host'      => $this->config['DB_HOST'] . ':' . $this->config['DB_PORT'],
            'database'  => $this->config['DB_DATABASE'],
            'username'  => $this->config['DB_USERNAME'],
            'password'  => $this->config['DB_PASSWORD'],
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        );
        new \Pixie\Connection('mysql', $config, 'DB');
        $this->routes = include __DIR__ . '/routes.php';
    }

    public function handleRequest()
    {
        $uri = $_SERVER['REQUEST_URI'];
        if (!in_array($uri, array_keys($this->routes))) {
            throw new \Exception('Page not found', 404);
        }
        $target = $this->routes[$uri];
        $segments = explode('@', $target);
        $method = count($segments) == 2
            ? $segments[1] : null;

        if (is_null($method)) {
            throw new \Exception('Method Not Provided.', 500);
        }
        $controllerName = ucfirst($segments[0]);
        $controllerName = "\App\Controllers\\" . $controllerName . "Controller";
        $reflector = new \ReflectionClass($controllerName);
        if (!$reflector->hasMethod($method)) {
            throw new \Exception('Method Not Provided.', 500);
        }
        $controller = new $controllerName;
        return $controller->$method();
    }

    private function __clone()
    {
        // declared as private to prevent cloning of an instance of the class via the clone operator
    }

    private function __wakeup()
    {
        // declared as private to prevent unserializing of an instance of the class via the global function unserialize()
    }

    public static function run()
    {
        if (empty(static::$instance)) {
            static::$instance = new Application();
        }
        return static::$instance;
    }

    public function __get($name)
    {
        if (!key_exists($name, $this->services)) {
            die('Service not exists!');
        }
        return $this->services[$name];
    }
}