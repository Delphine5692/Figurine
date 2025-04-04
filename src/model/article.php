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

            // fetchAll va chercher les infos (c'est à TOUS les articles) dans la base de données
            $articles = $stmt->fetchAll();


            return $articles;
        }       // fin de la partie protégée (les accès à la base sont terminés... pour le moment !)
        catch (PDOException $e) {
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
            // Requête SQL pour récupérer les 3 derniers articles triés par date de publication décroissante
            $query = 'SELECT * FROM ARTICLE ORDER BY date_publication DESC LIMIT 3';
            $stmt = $db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo ($e->getMessage());
            return null;
        }
    }
}
