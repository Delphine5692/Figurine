<?php
// Démarrage de la session
session_start();

// utilisation de Dotenv
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


use Figurine\Lib\DbConnector;
use Figurine\Controller\ProduitController;
use Figurine\Controller\ArticleController;


use Figurine\Controller\Router;
use Figurine\Controller\AccueilController;

$url = __DIR__ . "/";

$router = new Router('/');
// Ajouter une route pour la page d'accueil (méthode GET)
// $router->get('/', function() { 
//     echo "Bienvenue sur ma homepage !"; 
// });

// $router->get('/accueil', "AccueilController@afficherAccueil");
$router->get('/', function () {
    $controller = new AccueilController();
    $controller->afficherAccueil();
});
// $router->get('/', function($id){ echo "Bienvenue sur ma homepage !"; }); 
// $router->get('/posts/:id', function($id){ echo "Voila l'article $id"; }); 
$router->run();
