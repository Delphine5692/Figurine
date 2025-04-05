<?php

namespace Figurine\Model;

use mysqli;
use Figurine\Lib\DbConnector;

class Commentaire
{
    private $db;

    public function __construct()
    {
        $this->db = DbConnector::dbConnect();
    }
    // Récupérer les commentaires d'un article
    // public function getCommentaires($id_article)
    // {
    //     $pdo = DbConnector::dbConnect();
    //     $sql = "SELECT msg_blog, date_commentaire, id_utilisateur FROM COMMENTAIRE WHERE id_article = ?";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->bind_param("i", $id_article);
    //     $stmt->execute();
    //     $result = $stmt->get_result();
    //     $commentaires = [];
    //     while ($row = $result->fetch_assoc()) {
    //         $commentaires[] = $row;
    //     }
    //     return $commentaires;
    // }

    // Ajouter un commentaire dans la base de données
    public function ajouterCommentaire($msg_blog, $id_article, $id_utilisateur)
    {
        $pdo = DbConnector::dbConnect();
        $stmt = $pdo->prepare("INSERT INTO COMMENTAIRE (msg_blog, id_article, id_utilisateur) VALUES (?, ?, ?)");
        $stmt->execute([$msg_blog, $id_article, $id_utilisateur]);
    }

    public function getCommentaires($id_article)
    {
        $pdo = DbConnector::dbConnect();
        $stmt = $pdo->prepare("SELECT * FROM COMMENTAIRE WHERE id_article = ?");
        $stmt->execute([$id_article]);
        return $stmt->fetchAll();
    }
}
?>