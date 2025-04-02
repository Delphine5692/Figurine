<?php
// Démarrage de la session
session_start();

// utilisation de Dotenv
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

//require_once 'src/lib/config.php';
//require_once 'src/lib/dbConnector.php';
//require_once 'src/controller/articleController.php';

use Figurine\Lib\DbConnector;
use Figurine\controller\ProduitController;
use Figurine\controller\ArticleController;

// /*----- Pour tester (pourra être sipprimer à l'étape 2 du mail) -----*/
// try {
//     // Obtenir la connexion à la base de données
//     $db = DbConnector::dbConnect();

//     echo "Je suis connectée à la base de données";

// } catch (Exception $e) {
//     // Si une erreur se produit, afficher le message d'erreur
//     echo "Erreur : " . $e->getMessage();
// }
// /*----- Fin de test -----*/

// Appel du contrôleur
// Puisque la classe ProduitController ne dispose pas d'une méthode de classe (static),
// il faut instancier un objet de cette classe (les 2 techniques sont possibles, on en reparlera)
$controler = new ProduitController();
$controler->afficherProduits();             // appel de la methode d'affichage des produits

$controler = new ArticleController();
$controler->afficherArticles();     