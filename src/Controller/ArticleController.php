<?php

namespace Figurine\Controller;

use Figurine\Model\Article;
use Figurine\Model\Comment;
use Figurine\Lib\View;
use Figurine\Lib\FlashMessage;

class ArticleController
{
    /**
     * Affiche la liste de tous les articles.
     */
    public function showArticles()
    {
        // Récupérer tous les articles depuis le modèle
        $articles = Article::getAllArticles();

        // Si aucun article n'est trouvé, rediriger vers la page d'accueil avec un message
        if ($articles === null) {
            FlashMessage::addMessage('error', 'Aucun article trouvé.');
            View::render('home');
            exit();
        }

        View::render('liste-article', ['articles' => $articles]);
    }

    /**
     * Affiche un article spécifique par son ID.
     * Récupère l'article et ses commentaires associés.
     */
    public function showArticle($id)
    {
        // Récupère l'article qui correspond à l'ID donné
        $article = Article::getArticleById($id);

        // Récupère tous les commentaires liés à l'article
        $comments = (new Comment())->getComments($id);

        // Si l'article n'existe pas, ajoute un message flash et redirige vers la liste des articles
        if ($article === null) {
            FlashMessage::addMessage('error', "L'article n'existe pas.");
            header('Location: ' . BASE_URL . 'articles'); // Adaptation de l'URL selon votre route
            exit();
        }

        View::render('article-detail', [
            'article' => $article,
            'comments' => $comments
        ]);
    }

    /**
     * Ajoute un nouvel article.
     * - Valide les données du formulaire.
     * - Télécharge l'image dans le dossier dédié et crée un nom unique afin d'éviter les conflits.
     * - Enregistre l'article dans la base de données.
     * Redirige ensuite vers le tableau de bord admin avec un message flash.
     */
    public function addArticle()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titre'], $_POST['description'], $_FILES['image'])) {
            $title = htmlspecialchars($_POST['titre']);
            $description = htmlspecialchars($_POST['description']);
            $image = $_FILES['image'];

            $uploadDir = __DIR__ . '/../../public/uploads/';
            $uniqueFileName = uniqid() . '_' . basename($image['name']);
            $uploadFile = $uploadDir . $uniqueFileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($image['tmp_name'], $uploadFile)) {
                $imagePath = $uniqueFileName;
            } else {
                FlashMessage::addMessage('error', 'Erreur lors du téléchargement de l\'image.');
                header('Location: ' . BASE_URL . 'admin');
                exit;
            }

            $articleModel = new Article();
            if ($articleModel->addArticle($title, $description, $imagePath)) {
                FlashMessage::addMessage('success', 'Article ajouté avec succès.');
            } else {
                FlashMessage::addMessage('error', 'Une erreur est survenue lors de l\'ajout de l\'article.');
            }

            header('Location: ' . BASE_URL . 'admin');
            exit;
        }

        FlashMessage::addMessage('error', 'Données manquantes.');
        header('Location: ' . BASE_URL . 'admin');
        exit;
    }
}
