<?php

namespace Figurine\Controller;

use Figurine\Controller\Route;
use \Exception;

/**
 * Cette classe gère le système de routage de l'application.
 * Elle permet d'enregistrer des routes, de les associer à des méthodes HTTP 
 * (GET ou POST) et de trouver et appeler la route correspondante en fonction de l'URL.
 */
class Router
{

    private $url; // L'URL courante à traiter.
    private $routes = []; // Tableau associatif contenant les routes enregistrées pour chaque méthode HTTP.
    private $namedRoutes = []; // Tableau associatif des routes nommées pour générer des URL.
    private $queryParams; // Paramètres de la query string éventuellement passés à la requête.

    /**
     * Constructeur du Router.
     *
     * @param string $url L'URL à traiter.
     * @param array $queryParams (optionnel) les paramètres de la query string.
     */
    public function __construct($url, $queryParams = [])
    {
        $this->url = $url;
        $this->queryParams = $queryParams;
    }

    /**
     * Ajoute une route de type GET.
     *
     * @param string $path Le pattern de l'URL à matcher.
     * @param mixed $callable L'action à exécuter (contrôleur@méthode ou fonction anonyme).
     * @param string|null $name (optionnel) Le nom de la route pour générer des URL.
     * @return Route La route créée.
     */
    public function get($path, $callable, $name = null)
    {
        return $this->add($path, $callable, $name, 'GET');
    }

    /**
     * Ajoute une route de type POST.
     *
     * @param string $path Le pattern de l'URL à matcher.
     * @param mixed $callable L'action à exécuter.
     * @param string|null $name (optionnel) Le nom de la route.
     * @return Route La route créée.
     */
    public function post($path, $callable, $name = null)
    {
        return $this->add($path, $callable, $name, 'POST');
    }

    /**
     * Enregistre une route pour une méthode HTTP donnée.
     *
     * - Crée une instance de Route avec le pattern et le callable.
     * - Ajoute la route dans le tableau des routes pour la méthode spécifiée.
     * - Si un nom est fourni (ou déduit), enregistre la route dans le tableau des routes nommées.
     *
     * @param string $path Le pattern de la route.
     * @param mixed $callable La fonction ou le contrôleur@méthode à appeler.
     * @param string|null $name Le nom de la route.
     * @param string $method La méthode HTTP (GET ou POST).
     * @return Route La route enregistrée.
     */
    private function add($path, $callable, $name, $method)
    {
        // Crée une nouvelle instance de Route.
        $route = new Route($path, $callable);
        // Ajoute la route dans le tableau des routes sous la méthode HTTP spécifiée.
        $this->routes[$method][] = $route;

        // Si le callable est une chaîne et que aucun nom n'est fourni, on utilise le callable comme nom.
        if (is_string($callable) && $name === null) {
            $name = $callable;
        }
        // Si un nom est défini, on enregistre la route dans le tableau des routes nommées.
        if ($name) {
            $this->namedRoutes[$name] = $route;
        }
        return $route;
    }

    /**
     * Traite l'URL courante pour trouver et exécuter la route correspondante.
     *
     * - Vérifie si un groupe de routes est défini pour la méthode HTTP courante.
     * - Parcourt chaque route et teste si elle correspond à l'URL.
     * - Si une route correspond, l'action associée est exécutée et son résultat est retourné.
     * - Si aucune route ne correspond, affiche la page 404.
     *
     * @return mixed Le résultat de l'action de la route correspondante.
     * @throws Exception Si aucune route n'est définie pour la méthode HTTP.
     */
    public function run()
    {
        // Vérifie que des routes sont définies pour la méthode HTTP utilisée.
        if (!isset($this->routes[$_SERVER['REQUEST_METHOD']])) {
            throw new Exception('Aucune route définie pour cette méthode HTTP');
        }

        // Parcourt chaque route correspondant à la méthode HTTP courante.
        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
            // Teste si l'URL courante correspond au pattern de la route.
            if ($route->match($this->url)) {
                // Si une correspondance est trouvée, appelle l'action associée à cette route.
                return $route->call();
            }
        }
        // Si aucune route ne correspond, affiche la page d'erreur 404.
        $this->show404();
    }

    /**
     * Affiche la page 404.
     *
     * - Définit le code de réponse HTTP 404.
     * - Charge le fichier de vue de la page d'erreur 404.
     */
    private function show404()
    {
        http_response_code(404);
        require SRC_DIR . '/view/404.php';
    }

    /**
     * Génère une URL pour une route nommée.
     *
     * - Recherche la route par son nom dans le tableau des routes nommées.
     * - Utilise la méthode getUrl() de la route pour générer l'URL en y injectant d'éventuels paramètres.
     *
     * @param string $name Le nom de la route.
     * @param array $params (optionnel) Les paramètres à substituer dans le pattern de la route.
     * @return string L'URL générée pour la route nommée.
     * @throws Exception Si aucune route n'est trouvée avec ce nom.
     */
    public function url($name, $params = [])
    {
        if (!isset($this->namedRoutes[$name])) {
            throw new Exception('Aucune route trouvée avec ce nom');
        }
        return $this->namedRoutes[$name]->getUrl($params);
    }
}
