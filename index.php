<?php
// Démarrage de la session
session_start();

require 'vendor/autoload.php';

require_once 'src/lib/config.php';
require_once 'src/lib/dbConnector.php';
require_once 'src/controller/articleController.php';

use Figurine\Lib\DbConnector;
use Figurine\controller\ArticleController;

try {
    // Obtenir la connexion à la base de données
    $db = DbConnector::dbConnect();

    echo "Je suis connectée à la base de données";

} catch (Exception $e) {
    // Si une erreur se produit, afficher le message d'erreur
    echo "Erreur : " . $e->getMessage();
}





