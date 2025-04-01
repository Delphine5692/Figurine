<?php

// public function = définition d'une fonction sur une instance d'un objet, qui aura accès à $this, et aux valeurs des variables d'instance, ET aux variables statiques.

// public static function = définition d'une fonction sur une classe, qui n'aura pas accès à $this, ni aux valeurs des variables d'instance, mais qui aura toujours accès aux variables statiques.

namespace Figurine\Model;

// use Figurine\Model;

class Article
{

     public function __construct(int $id, string $titre, string $image, string $description, string $date_publication)
     //  fetch_obj ou class à la place du fetch_assoc
    {

    }

    // Méthode pour récupérer tous les articles depuis la base de données
    public static function getAllArticles($db)
    {
        $query = 'SELECT * FROM article';
        $stmt = $db->prepare($query); // prepare, évite les injections sql
        $stmt->execute();
        $articles = [];

        // fetch va chercher les infos dans la base de données
        while ($row = $stmt->fetch()) {
            $articles[] = new Article(
                $row['id_article'],
                $row['titre'],
                $row['image'],
                $row['description'],
                $row['date_publication']
            );
        }

        return $articles;
    }
}
