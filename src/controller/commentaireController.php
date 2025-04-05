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
            var_dump($_POST); // Vérifie les données envoyées par le formulaire
            $msg_blog = $_POST['msg_blog'] ?? null;
            $id_article = $_POST['id_article'] ?? null;
            $id_utilisateur = $_SESSION['id_utilisateur'];

            if (!$id_article) {
                echo "Erreur : ID de l'article manquant.";
                return;
            }

            $this->commentaireModel->ajouterCommentaire($msg_blog, $id_article, $id_utilisateur);

            header("Location: /web/Figurine/index.php?url=article&id=$id_article");
            exit();
        } else {
            echo "Vous devez être connecté pour ajouter un commentaire.";
        }
    }

    // // Supprimer un commentaire
    // public function supprimerCommentaire($id_commentaire)
    // {
    //     $this->commentaireModel->supprimerCommentaire($id_commentaire);
    //     header("Location: /web/Figurine/index.php?url=article&id=$id_article");
    //     exit();
    // }
}
