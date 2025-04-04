<?php
// Démarrage de la session
session_start();

require 'vendor/autoload.php';

// Utilisation de Dotenv
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Figurine\Controller\Router;
use Figurine\Controller\AccueilController;
use Figurine\Controller\ProduitController;
use Figurine\Controller\ArticleController;


// On récupère l'URL dans l'URL (ou '/' si vide)
$url = $_GET['url'] ?? '/';

$router = new Router($url);

// Page d'accueil
$router->get('/', function () {
    $controller = new AccueilController();
    $controller->afficherAccueil();
});

// Page des produits
$router->get('/produits', function () {
    $controller = new ProduitController();
    $controller->afficherProduits(); // méthode à créer dans ton contrôleur Produit
});

// Page d’un produit avec paramètre (id)
$router->get('/produits/:id', function ($id) {
    $controller = new ProduitController();
    $controller->afficherProduits($id); // méthode à créer
});

// Page des articles
$router->get('/articles', function () {
    $controller = new ArticleController();
    $controller->afficherArticles();
});

// Route pour afficher un article spécifique par ID
$router->get('/article/:id', function ($id) {
    $controller = new ArticleController();
    $controller->afficherArticle($id);
});


$router->run();
