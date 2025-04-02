<?php

namespace Figurine\Controller;

use Figurine\model\Article;

class ArticleController
{
    public function afficherArticles()
    {

        $articles = Article::getAllArticles();

        // Inclure la vue et passer les articles
        require_once 'src/view/view-article.php';
    }
}
