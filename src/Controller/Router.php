<?php

namespace Figurine\Controller;

use Figurine\Controller\Route;
use \Exception;

class Router
{

    private $url;
    private $routes = [];
    private $namedRoutes = [];
    private $queryParams;

    public function __construct($url, $queryParams = [])
    {
        $this->url = $url;
        $this->queryParams = $queryParams;
    }

    public function get($path, $callable, $name = null)
    {
        return $this->add($path, $callable, $name, 'GET');
    }

    public function post($path, $callable, $name = null)
    {
        return $this->add($path, $callable, $name, 'POST');
    }

    private function add($path, $callable, $name, $method)
    {
        $route = new Route($path, $callable);
        $this->routes[$method][] = $route;
        if (is_string($callable) && $name === null) {
            $name = $callable;
        }
        if ($name) {
            $this->namedRoutes[$name] = $route;
        }
        return $route;
    }

    public function run()
    {
        if (!isset($this->routes[$_SERVER['REQUEST_METHOD']])) {
            throw new Exception('Aucune route défini pour cette méthode HTTP');
        }

        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
            if ($route->match($this->url)) {
                return $route->call();
            }
        }
        $this->show404();
    }

    private function show404()
    {
        http_response_code(404);
        require SRC_DIR . '/view/404.php';
    }


    public function url($name, $params = [])
    {
        if (!isset($this->namedRoutes[$name])) {
            throw new Exception('Aucune route trouvée avec ce nom');
        }
        return $this->namedRoutes[$name]->getUrl($params);
    }

}
