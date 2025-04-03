<?php

namespace Figurine\Model;

use Figurine\Lib\DbConnector;
use PDOException;

class Article
{
    // Méthode pour récupérer tous les produits depuis la base de données
    public static function getAllArticles()
    {
        try {       // protection de la zone de code où une erreur est possible
            // récupération du connecteur de base en sollicitant la classe singleton
            $db = DbConnector::dbConnect();

            $query = 'SELECT * FROM ARTICLE';
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
}
