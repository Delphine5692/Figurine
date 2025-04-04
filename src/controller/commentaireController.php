<?php

namespace Figurine\Controller;

use Figurine\Model\Commentaire;

class CommentaireController
{
    private $commentaireModel;

    public function __construct()
    {
        // Assurez-vous d'utiliser le bon modèle de commentaires
        $this->commentaireModel = new Commentaire();
    }

    // Afficher les commentaires d'un article
    public function afficherCommentaires($id_article)
    {
        $commentaires = $this->commentaireModel->getCommentaires($id_article);
        require 'vue/commentaires.php'; // Affiche la vue des commentaires
    }

    // Ajouter un commentaire
    public function ajouterCommentaire()
    {
        session_start();

        if (isset($_SESSION['id_utilisateur']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
            $msg_blog = $_POST['msg_blog'];
            $id_article = $_POST['id_article'];
            $id_utilisateur = $_SESSION['id_utilisateur'];

            $this->commentaireModel->ajouterCommentaire($msg_blog, $id_article, $id_utilisateur);

            // Redirige vers la page de l'article après ajout
            header("Location: /article/$id_article/commentaires");
            exit();
        } else {
            echo "Vous devez être connecté pour ajouter un commentaire.";
        }
    }
}
?>
