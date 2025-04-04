<?php

namespace Figurine\Model;

use Figurine\Lib\DbConnector;
use PDOException;

class Article
{
    // Méthode pour récupérer tous les produits depuis la base de données
    public static function getAllArticles()
    {
        try {

            $db = DbConnector::dbConnect();

            // Requête SQL pour récupérer tous les articles triés par date de publication décroissante
            $query = 'SELECT * FROM ARTICLE ORDER BY date_publication DESC';
            $stmt = $db->prepare($query);   // prepare, évite les injections sql
            $stmt->execute();

            // fetchAll va chercher les infos (c'est à dire TOUS les articles) dans la base de données
            $articles = $stmt->fetchAll();

            return $articles;
        } catch (PDOException $e) {
            // pour le moment on affiche "sauvagement" l'erreur ; on verra plus subtil ultérieurement
            echo ($e->getMessage());
            return null;
        }
    }

    // Méthode pour récupérer un article par son ID
    public static function getArticleById($id)
    {
        try {

            $db = DbConnector::dbConnect();

            $query = 'SELECT * FROM ARTICLE WHERE id_article = :id';
            $stmt = $db->prepare($query);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT); // protection contre injection SQL
            $stmt->execute();

            $article = $stmt->fetch();

            return $article;
        } catch (PDOException $e) {
            echo ($e->getMessage());
            return null;
        }
    }

    // Récupérer les 3 derniers articles triés par date de publication décroissante
    public static function getLastThreeArticles()
    {
        try {

            $db = DbConnector::dbConnect();

            $query = 'SELECT * FROM ARTICLE ORDER BY date_publication DESC LIMIT 3';
            $stmt = $db->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo ($e->getMessage());
            return null;
        }
    }
 
     // Récupérer les commentaires d'un article spécifique
     public static function getCommentairesByArticle($id_article)
     {
         try {
             $db = DbConnector::dbConnect();
             $query = 'SELECT c.msg_blog, c.date_commentaire, u.nom, u.prenom FROM COMMENTAIRE c
                       INNER JOIN UTILISATEUR u ON c.id_utilisateur = u.id_utilisateur
                       WHERE c.id_article = :id_article ORDER BY c.date_commentaire DESC';
             $stmt = $db->prepare($query);
             $stmt->bindParam(':id_article', $id_article, \PDO::PARAM_INT);
             $stmt->execute();
             $commentaires = $stmt->fetchAll();
             return $commentaires;
         } catch (PDOException $e) {
             echo ($e->getMessage());
             return null;
         }
     }
}
