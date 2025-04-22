<?php

namespace Figurine\Controller;

/**
 * Cette classe représente une route du système de routage.
 * Elle stocke le chemin (pattern) et l'action (callable) à exécuter lorsque la route correspond à l'URL.
 */
class Route
{

    // Propriété de la classe Route
    private $path; // Chemin de l'URL ('Par exemple : /produits/:id')
    private $callable; // Le couple classe@méthode de l'action à réaliser ('ProduitController@showProductDetails')
    private $matches = []; // Correspondances des paramètres de l'URL
    private $params = []; // Paramètres de la route (id, slug, ...)


    /**
     * Constructeur de la classe Route.
     *
     * @param string $path Le chemin (pattern) de la route.
     * @param mixed  $callable Le contrôleur@méthode ou fonction à exécuter.
     */
    public function __construct($path, $callable)
    {
        // On retire les '/' superflus en début et fin de chaîne.
        $this->path = trim($path, '/');
        $this->callable = $callable;
    }

    /**
     * Vérifie si une URL correspond au pattern de la route.
     *
     * Cette méthode effectue les étapes suivantes :
     * - Supprime les '/' en début et fin de l'URL fournie.
     * - Remplace les paramètres dynamiques (ex. :id) par une expression régulière qui capture la valeur.
     * - Construit une expression régulière complète pour la route et teste l'URL.
     * - Si l'URL correspond, sauvegarde les valeurs capturées dans $matches et retourne true.
     * - Sinon, retourne false.
     *
     * @param string $url L'URL à tester.
     * @return bool Vrai si l'URL correspond à la route, sinon faux.
     */
    public function match($url)
    {
        // On retire les '/' superflus de l'URL.
        $url = trim($url, '/');

        // Remplace les paramètres de la route (ex: :id) par une regex pour capturer la valeur.
        $path = preg_replace('#:([\w]+)#', '([^/]+)', $this->path);

        // Création de la regex complète : commence (^) et se termine ($) par le pattern.
        $regex = "#^$path$#i";

        // Vérifie si l'URL correspond à l'expression régulière.
        if (!preg_match($regex, $url, $matches)) {
            // Aucun match trouvé, la route ne correspond pas.
            return false;
        }

        // Le premier élément de $matches est l'URL complète, on le retire.
        array_shift($matches);

        // Stocke les valeurs capturées dans la propriété $matches pour utilisation ultérieure.
        $this->matches = $matches;

        // La route correspond à l'URL.
        return true;
    }

    /**
     * Appelle l'action associée à la route.
     *
     * Cette méthode exécute l'action liée à la route :
     * - Si $callable est une chaîne au format "NomClasse@nomMéthode", elle instancie la classe correspondante
     *   (en ajoutant le namespace courant) et appelle la méthode avec les paramètres capturés.
     * - Si $callable est une fonction anonyme ou autre callable, elle l'exécute directement.
     *
     * @return mixed Le résultat de l'appel de la fonction ou de la méthode.
     */
    public function call()
    {
        // Si $callable est une chaîne (ex. "ProductController@showProductDetails")
        if (is_string($this->callable)) {
            // Sépare la chaîne en nom de classe et nom de la méthode.
            $params = explode('@', $this->callable);

            // Construit le nom complet de la classe en ajoutant le namespace courant.
            $pathClass = __NAMESPACE__ . "\\" . $params[0];

            // Crée une instance du contrôleur.
            $controller = new $pathClass();

            // Utilise call_user_func_array pour appeler la méthode en passant $this->matches comme arguments.
            return call_user_func_array([$controller, $params[1]], $this->matches);
        } else {
            // Si $callable est déjà un callable (par exemple, une fonction anonyme), on l'appelle directement.
            return call_user_func_array($this->callable, $this->matches);
        }
    }
}
