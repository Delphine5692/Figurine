<?php

namespace Figurine\Controller;

use Figurine\Controller\ProduitController;
use Figurine\Controller\ArticleController;
use Figurine\Controller\Router;


class Route
{

    private $path;
    private $callable;
    private $matches = [];
    private $params = [];

    public function __construct($path, $callable)
    {
        $this->path = trim($path, '/');  // On retire les / inutils
        $this->callable = $callable;
    }

    /**
     * Permettra de capturer l'url avec les paramètre 
     * get('/posts/:slug-:id') par exemple
     **/
    public function match($url)
    {
        $url = trim($url, '/');
        $path = preg_replace('#:([\w]+)#', '([^/]+)', $this->path);
        $regex = "#^$path$#i";
        if (!preg_match($regex, $url, $matches)) {
            return false;
        }
        array_shift($matches);
        $this->matches = $matches;  // On sauvegarde les paramètre dans l'instance pour plus tard
        return true;
    }

    public function call()
    {
        if (is_string($this->callable)) {
            $params = explode('@', $this->callable);
            $pathClass = __NAMESPACE__ . "\\" . $params[0];

            $controller = new $pathClass();


            return call_user_func_array([$controller, $params[1]], $this->matches);
        } else {
            return call_user_func_array($this->callable, $this->matches);
        }
    }
    
}
