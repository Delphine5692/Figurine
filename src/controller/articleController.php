<?php

namespace Figurine\Controller;

use Figurine\model\Article;

class ArticleController
{
    public function afficherArticles()
    {

        $articles = Article::getAllArticles();

        if ($articles === null) {
            header('Location: /view-liste-article.php');
            exit();
        }

        // Inclure la vue et passer les articles
        require_once 'src/view/view-liste-article.php';
    }

    // Méthode pour afficher un article spécifique par ID
    public function afficherArticle($id)
    {
        $article = Article::getArticleById($id);
        $commentaires = Article::getCommentairesByArticle($id);

        if ($article === null) {
            header('Location: /view-liste-article.php');
            exit();
        }

        // var_dump($article); // Vérifie les données de l'article
        // var_dump($commentaires); // Vérifie les commentaires

        require_once 'src/view/view-article-detail.php'; // Vue détaillée pour afficher l'article
    }
}
