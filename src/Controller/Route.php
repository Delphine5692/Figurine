<?php

namespace Figurine\Controller;

use Figurine\Controller\ProductController;
use Figurine\Controller\Article;
use Figurine\Controller\Router;


class Route
{

    // Propriété de la classe Route
    private $path; // Chemin de l'URL ('Par exemple : /produits/:id')
    private $callable; // Le couple classe@méthode de l'action à réaliser ('ProduitController@showProductDetails')
    private $matches = []; // Correspondances des paramètres de l'URL
    private $params = []; // Paramètres de la route (id, slug, ...)


    // Constructeur de la classe Route
    public function __construct($path, $callable)
    {
        // On retire les / inutiles
        $this->path = trim($path, '/');
        $this->callable = $callable;
    }

    // Méthode pour obtenir le chemin de la route
    public function match($url)
    {
        // On retire les / inutiles
        $url = trim($url, '/');

        // Remplace les paramètres de la route par des regex
        $path = preg_replace('#:([\w]+)#', '([^/]+)', $this->path);

        // Création de la regex pour la route
        $regex = "#^$path$#i";

        // Vérification si l'URL correspond au schéma la route
        if (!preg_match($regex, $url, $matches)) {
            return false;       // Si pas de correspondance, on retourne false
        }

        // Retire le premier élément du tableau $matches (URL complète)
        array_shift($matches);

        // On sauvegarde les paramètre dans l'instance pour plus tard
        $this->matches = $matches;

        // L'URL correspond à la route, on peut l'appeler
        return true;
    }

    // Méthode pour appeler l'action associée à la route
    public function call()
    {
        // Si la route est une chaine de caractères
        if (is_string($this->callable)) {

            // On sépare le nom de la classe et la méthode
            $params = explode('@', $this->callable);

            // On génère le nom de la classe à partir du namespace et de la classe
            $pathClass = __NAMESPACE__ . "\\" . $params[0];

            // Instantie la classe
            $controller = new $pathClass();

            // Appelle la méthode de la classe avec les paramètres
            return call_user_func_array([$controller, $params[1]], $this->matches);
        } else {
            return call_user_func_array($this->callable, $this->matches);
        }
    }
}
