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
use Figurine\Controller\CommentaireController;
use Figurine\Controller\UtilisateurController;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// On récupère l'URL dans l'URL (ou '/' si vide)
$url = $_GET['url'] ?? '/';

$router = new Router($_GET['url'] ?? '/');
// $router = new Router($url);

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

// Route pour ajouter un commentaire
$router->post('/commentaire/ajouter', function () {
    $controller = new CommentaireController();
    $controller->ajouterCommentaire();
});

// Route pour afficher les commentaires d'un article spécifique
$router->get('/article/:id/commentaires', function ($id) {
    $controller = new CommentaireController();
    $controller->afficherCommentaires($id);
});

$router->get('/login', function () {
    require __DIR__ . '/src/view/connexion.php';
});

$router->post('/login', function () {
    $controller = new UtilisateurController();
    $controller->connexion();
});

$router->get('/logout', function () {
    session_start();
    session_destroy();
    header('Location: /');
});


$router->get('/inscription', function () {
    $controller = new UtilisateurController();
    $controller->inscription();  // Affiche le formulaire d'inscription
});

$router->get('/connexion', function () {
    $controller = new UtilisateurController();
    $controller->connexion();  // Affiche le formulaire de connexion
});

// Route POST pour soumettre le formulaire d'inscription
$router->post('/inscription', function () {
    $controller = new UtilisateurController();
    $controller->inscription();  // Traite l'inscription
});


$router->post('/inscription', 'UtilisateurController@inscription');


var_dump($_POST);  // Vérifie que les données sont envoyées

var_dump($_SERVER['REQUEST_URI']);

echo "<pre>URL demandée : " . $_GET['url'] . "</pre>";


$router->run();
