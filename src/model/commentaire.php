<?php

namespace Figurine\Model;

use mysqli;

class Commentaire
{
    private $db;

    public function __construct()
    {
        $this->db = new mysqli("localhost", "username", "password", "figurine_bdd"); // Connecte la base de données
    }

    // Récupérer les commentaires d'un article
    public function getCommentaires($id_article)
    {
        $sql = "SELECT msg_blog, date_commentaire, id_utilisateur FROM COMMENTAIRE WHERE id_article = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_article);
        $stmt->execute();
        $result = $stmt->get_result();
        $commentaires = [];
        while ($row = $result->fetch_assoc()) {
            $commentaires[] = $row;
        }
        return $commentaires;
    }

    // Ajouter un commentaire dans la base de données
    public function ajouterCommentaire($msg_blog, $id_article, $id_utilisateur)
    {
        $sql = "INSERT INTO COMMENTAIRE (msg_blog, date_commentaire, id_article, id_utilisateur) 
                VALUES (?, NOW(), ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sii", $msg_blog, $id_article, $id_utilisateur);
        return $stmt->execute();
    }
}
?>
