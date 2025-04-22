<?php

namespace Figurine\Controller;

use Figurine\Model\Comment;
use Figurine\Lib\View;
use Figurine\Lib\FlashMessage;

class CommentController
{
    /**
     * Instance du modèle Comment utilisée pour interagir avec la base.
     * @var Comment
     */
    private $commentModel;

    /**
     * Constructeur du CommentController.
     * Initialise le modèle de commentaires pour pouvoir effectuer les opérations (lecture, ajout, suppression).
     */
    public function __construct()
    {
        // Initialise le modèle pour par la suite gérer les commentaires.
        $this->commentModel = new Comment();
    }

    /**
     * Affiche les commentaires d'un article spécifique.
     *
     * - Valide l'ID de l'article à l'aide d'un filtre pour prévenir les erreurs.
     * - Si l'ID est invalide, ajoute un message d'erreur et redirige vers la page d'accueil.
     * - Récupère les commentaires associés à l'article et les affiche dans la vue 'comment'.
     *
     * @param int $id_article L'identifiant unique de l'article.
     */
    public function showComments($id_article)
    {
        // Valide l'ID de l'article.
        $id_article = filter_var($id_article, FILTER_VALIDATE_INT);
        if (!$id_article) {
            FlashMessage::addMessage('error', "ID de l'article invalide.");
            header("Location: accueil"); // Redirige vers la page par défaut.
            exit();
        }

        // Récupère les commentaires liés à l'article.
        $comments = $this->commentModel->getComments($id_article);

        // Affiche la vue contenant les commentaires.
        View::render('comment', ['comments' => $comments]);
    }

    /**
     * Ajoute un commentaire via le formulaire.
     *
     * - Vérifie que l'utilisateur est connecté et que la requête est POST.
     * - Valide et nettoie les données du formulaire (le message et l'ID de l'article).
     * - Si le message est vide ou l'ID de l'article invalide, ajoute un message d'erreur et redirige.
     * - Tente d'ajouter le commentaire dans la base ; en cas de succès, ajoute un message de succès, sinon un message d'erreur.
     * - Redirige vers la page de l'article après traitement.
     */
    public function addComment()
    {
        if (isset($_SESSION['id_utilisateur']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupère et nettoie le message du commentaire.
            $msg_blog   = trim($_POST['msg_blog'] ?? '');
            // Valide l'ID de l'article.
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
                // Tente d'ajouter le commentaire dans la base de données.
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
                // Capture les exceptions et affiche l'erreur.
                FlashMessage::addMessage('error', $e->getMessage());
                header("Location: article/{$id_article}");
                exit();
            }
        } else {
            // Si l'utilisateur n'est pas connecté ou la méthode n'est pas POST.
            FlashMessage::addMessage('error', "Vous devez être connecté pour ajouter un commentaire.");
            header("Location: article/" . ($_POST['id_article'] ?? ''));
            exit();
        }
    }

    /**
     * Supprime un commentaire.
     *
     * - Vérifie que l'utilisateur est connecté.
     * - Valide que la requête est POST et que l'ID du commentaire est présent et valide.
     * - Vérifie que le commentaire appartient à l'utilisateur connecté.
     * - Supprime le commentaire en base et affiche un message de succès, sinon un message d'erreur.
     * - Redirige vers la page de profil après suppression.
     */
    public function deleteComment()
    {
        // Vérifie si l'utilisateur est connecté.
        if (!isset($_SESSION['id_utilisateur'])) {
            FlashMessage::addMessage('error', "Vous devez être connecté pour supprimer un commentaire.");
            header('Location: login');
            exit();
        }

        // Vérifie que la requête est POST et que l'ID du commentaire est fourni.
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_commentaire'])) {
            // Valide l'ID du commentaire.
            $id_comment = filter_input(INPUT_POST, 'id_commentaire', FILTER_VALIDATE_INT);
            $id_user = $_SESSION['id_utilisateur'];

            if (!$id_comment) {
                FlashMessage::addMessage('error', "ID de commentaire invalide.");
                header('Location: profil');
                exit();
            }

            // Vérifie l'appartenance du commentaire à l'utilisateur connecté.
            if ($this->commentModel->verifyCommentOwner($id_comment, $id_user)) {
                // Supprime le commentaire.
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

        // Si la requête n'est pas valide, ajoute un message d'erreur.
        FlashMessage::addMessage('error', "Requête invalide pour la suppression du commentaire.");
        header('Location: profil');
        exit();
    }
}