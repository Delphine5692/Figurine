<?php

namespace Figurine\Controller;

use Figurine\model\Article;

class ArticleController
{
    public function afficherArticles()
    {

        $articles = Article::getAllArticles();

        if ($articles === null) {
            header('Location: /view-article.php');
            exit();
        }

        // Inclure la vue et passer les articles
        require_once 'src/view/view-article.php';
    }
}
