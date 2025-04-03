<?php

namespace Figurine\Controller;

use Figurine\model\Accueil;

class AccueilController
{
    public function afficherAccueil()
    {

        // $articles = Article::getAllArticles();

        // if ($articles === null) {
        //     header('Location: /view-article.php');
        //     exit();
        // }

        // Inclure la vue et passer les articles
        require_once 'src/view/view-accueil.php';
    }
}
