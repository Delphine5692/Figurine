<?php

namespace Figurine\Controller;

use Figurine\Model\Produit;
use Figurine\Model\Article;

class AccueilController
{
    public function afficherAccueil()
    {

        $articles = Article::getAllArticles();
        $produits = Produit::getAllProduits();

        // $articles = Article::getAllArticles();

        // if ($articles === null) {
        //     header('Location: /view-article.php');
        //     exit();
        // }

        // Inclure la vue et passer les articles
        require_once 'src/view/view-accueil.php';
    }
}
