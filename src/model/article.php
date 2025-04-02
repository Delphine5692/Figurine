<?php

// public function = définition d'une fonction sur une instance d'un objet, qui aura accès à $this, et aux valeurs des variables d'instance, ET aux variables statiques.

// public static function = définition d'une fonction sur une classe, qui n'aura pas accès à $this, ni aux valeurs des variables d'instance, mais qui aura toujours accès aux variables statiques.

// namespace Figurine\Model;

// // use Figurine\Model;

// class Article
// {

//      public function __construct(int $id, string $titre, string $image, string $description, string $date_publication)
//      //  fetch_obj ou class à la place du fetch_assoc
//     {

//     }

    // Méthode pour récupérer tous les articles depuis la base de données
    // public static function getAllArticles($db)
    // {
    //     $query = 'SELECT * FROM article';
    //     $stmt = $db->prepare($query); // prepare, évite les injections sql
    //     $stmt->execute();
    //     $articles = [];

        // fetch va chercher les infos dans la base de données
//         while ($row = $stmt->fetch()) {
//             $articles[] = new Article(
//                 $row['id_article'],
//                 $row['titre'],
//                 $row['image'],
//                 $row['description'],
//                 $row['date_publication']
//             );
//         }

//         return $articles;
//     }
// }


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
            $db=DbConnector::dbConnect();

            $query = 'SELECT * FROM ARTICLE';
            $stmt = $db->prepare($query);   // prepare, évite les injections sql
            $stmt->execute();
            //$produits = [];

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
