<?php

namespace Figurine\Controller;

use Figurine\Model\Comment;
use Figurine\Model\Order;
use Figurine\Model\Article;
use Figurine\Model\User;
use Figurine\Lib\View;
use Figurine\Lib\FlashMessage;

class CommentController
{
    private $commentModel;

    /**
     * Constructeur de la classe CommentController.
     * Initialise le modèle de commentaires pour les opérations liées aux commentaires.
     */
    public function __construct()
    {
        // Initialise le modèle de commentaires
        $this->commentModel = new Comment();
    }

    /**
     * Affiche les commentaires d'un article spécifique.
     * Récupère les commentaires liés à l'article depuis le modèle et les affiche dans une vue.
     * 
     * @param int $id_article L'identifiant unique de l'article.
     */
    public function showComments($id_article)
    {
        // Valide l'ID de l'article
        $id_article = filter_var($id_article, FILTER_VALIDATE_INT);
        if (!$id_article) {
            FlashMessage::addMessage('error', "ID de l'article invalide.");
            header("Location: accueil"); // rediriger vers une page par défaut (accueil, par exemple)
            exit();
        }

        // Récupère les commentaires liés à l'article
        $comments = $this->commentModel->getComments($id_article);

        // Affiche les commentaires dans la vue correspondante
        View::render('comment', ['comments' => $comments]);
    }


    private function getArticleAndComments(int $id_article): array
    {
        $articleModel = new Article();
        $article = $articleModel->getArticleById($id_article);
        $comments  = $this->commentModel->getComments($id_article);

        return [
            'article' => $article,
            'comments' => $comments
        ];
    }


    /**
     * Ajoute un commentaire via un formulaire.
     * Valide les données envoyées par le formulaire et enregistre le commentaire dans la base de données.
     * Redirige vers la page de l'article après soumission.
     */
    public function addComment()
    {
        if (isset($_SESSION['id_utilisateur']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $msg_blog   = trim($_POST['msg_blog'] ?? '');
            $id_article = filter_input(INPUT_POST, 'id_article', FILTER_VALIDATE_INT);
            $id_user    = $_SESSION['id_utilisateur'];

            if (!$id_article) {
                FlashMessage::addMessage('error', "ID d'article invalide.");
                header("Location: article/{$id_article}");
                exit();
            }

            if (empty($msg_blog)) {
                FlashMessage::addMessage('error', "Votre commentaire est vide.");
                header("Location: article/{$id_article}");
                exit();
            }

            try {
                $result = $this->commentModel->addComment($msg_blog, $id_article, $id_user);
                if ($result) {
                    FlashMessage::addMessage('success', "Commentaire ajouté avec succès.");
                    header("Location: article/{$id_article}");
                    exit();
                } else {
                    FlashMessage::addMessage('error', "Erreur lors de l'ajout du commentaire.");
                    header("Location: article/{$id_article}");
                    exit();
                }
            } catch (\InvalidArgumentException $e) {
                FlashMessage::addMessage('error', $e->getMessage());
                header("Location: article/{$id_article}");
                exit();
            }
        } else {
            FlashMessage::addMessage('error', "Vous devez être connecté pour ajouter un commentaire.");
            header("Location: article/" . ($_POST['id_article'] ?? ''));
            exit();
        }
    }

    /**
     * Supprime un commentaire.
     * Vérifie si l'utilisateur est connecté et s'il est autorisé à supprimer le commentaire.
     * Recharge les données du profil après suppression.
     */
    public function deleteComment()
    {
        // Vérifie si l'utilisateur est connecté
        if (!isset($_SESSION['id_utilisateur'])) {
            FlashMessage::addMessage('error', "Vous devez être connecté pour supprimer un commentaire.");
            header('Location: login');
            exit();
        }

        // Vérifie si la requête est de type POST et si l'ID du commentaire est présent
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_commentaire'])) {
            // Récupère et valide l'ID du commentaire
            $id_comment = filter_input(INPUT_POST, 'id_commentaire', FILTER_VALIDATE_INT);
            $id_user = $_SESSION['id_utilisateur'];

            if (!$id_comment) {
                FlashMessage::addMessage('error', "ID de commentaire invalide.");
                header('Location: profil');
                exit();
            }

            // Vérifie que le commentaire appartient à l'utilisateur connecté
            if ($this->commentModel->verifyCommentOwner($id_comment, $id_user)) {
                // Supprime le commentaire
                $this->commentModel->deleteComment($id_comment);
                FlashMessage::addMessage('success', "Commentaire supprimé avec succès.");
                header('Location: profil');
                exit();
            } else {
                FlashMessage::addMessage('error', "Vous n'êtes pas autorisé à supprimer ce commentaire.");
                header('Location: profil');
                exit();
            }
        }

        FlashMessage::addMessage('error', "Requête invalide pour la suppression du commentaire.");
        header('Location: profil');
        exit();
    }
}
