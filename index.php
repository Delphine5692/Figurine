<?php
// Démarrage de la session
// Prolonger la durée de vie des sessions (par exemple, 10 jours en secondes)
ini_set('session.gc_maxlifetime', 864000);
session_set_cookie_params(864000);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'vendor/autoload.php';

// Utilisation de Dotenv
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Figurine\Controller\Router;

// Définitions de constantes pour "rebaser" les liens (voir dans les vues)
define("URL_PATH", dirname($_SERVER['PHP_SELF']) . "/");
define("FULL_URL_PATH", 
    ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')? "https://": "http://").$_SERVER['HTTP_HOST'].URL_PATH
);

// Chemins internes au backend
define ("SRC_DIR", __DIR__ . "/src");


define("BASE_URL", dirname($_SERVER['SCRIPT_NAME']) . '/');

// On récupère l'URL (relative au site) et les query parameters
if (isset($_GET['url'])) {
    $url = $_GET['url'];
    $queryParams = $_GET;
    array_shift($queryParams);
}
else {
    $url = '/';
    $queryParams = $_GET;
};


// Création du router et enregistrement des routes
$router = new Router($url, $queryParams);


// Page d'accueil
$router->get('/', 'HomeController@showHome');
$router->get('home', 'HomeController@showHome');

// Page des articles
$router->get('/articles', 'ArticleController@showArticles');

// Route pour afficher un article spécifique par ID
$router->get('/article/:id', 'ArticleController@showArticle');

// Route pour ajouter un commentaire
$router->post('/commentaire', 'CommentController@addComment');

// Route pour afficher les commentaires d'un article spécifique
$router->get('/article/:id/comments', 'CommentController@showComments');

// Routes pour la gestion des utilisateurs
$router->get('/login', 'UserController@login'); // Affiche le formulaire de connexion
$router->post('/login', 'UserController@login'); // Traite la connexion
$router->get('/logout', 'UserController@logout'); // Déconnexion
$router->get('/create-account', 'UserController@createAccount'); // Affiche le formulaire d'inscription
$router->post('/create-account', 'UserController@createAccount'); // Traite l'inscription
$router->get('/profil', 'UserController@showProfile'); // Affiche le profil utilisateur
$router->post('/supprimer-compte', 'UserController@deleteAccount'); // Supprime un compte utilisateur
$router->post('/modifier-adresse', 'UserController@updateAddress'); // Modifie l'adresse utilisateur

// Routes pour les produits
$router->get('/products', 'ProductController@showProducts'); // Liste des produits
$router->get('/products/latest', 'ProductController@showLatestProducts'); // Liste des derniers produits

$router->post('/product/ajouter-produit', 'ProductController@addProduct'); // Ajoute un article
$router->post('/productReview', 'ReviewController@addReview'); // Ajoute un avis sur un produit
$router->post('/supprimer-avis', 'ReviewController@deleteReview'); // Supprime un avis sur un produit

$router->get('/product/:id', 'ProductController@showProductDetails'); // Détail d'un produit

// Routes pour le panier
$router->get('/panier', 'OrderController@showCart'); // Affiche le panier
$router->post('/valider-panier', 'OrderController@validateCart'); // Valide le panier

// Routes pour les commandes
$router->post('/supprimer-commentaire', 'CommentController@deleteComment'); // Supprime un commentaire

// Routes pour l'administrateur
$router->get('/admin', 'AdminController@showDashboard'); // Affiche le tableau de bord admin
$router->post('/admin/supprimer-utilisateur', 'AdminController@deleteUser'); // Supprime un utilisateur

$router->post('/admin/supprimer-article', 'AdminController@supprimerArticle'); // Supprime un article
$router->post('/admin/ajouter-produit', 'AdminController@ajouterProduit'); // Ajoute un produit
$router->post('/admin/supprimer-produit', 'AdminController@supprimerProduit'); // Supprime un produit

$router->post('/article/ajouter-article', 'ArticleController@addArticle'); // Ajoute un article

$router->get('/contact', 'ContactController@showContact');
$router->post('/contact/send', 'ContactController@sendContact');
$router->get('/contact/send', 'ContactController@sendContact');

$router->get('/about-us', 'PageController@aboutUs');

$router->get('/admin/contact-messages', 'AdminController@contactMessages');

// Exécute le routeur
$router->run();
