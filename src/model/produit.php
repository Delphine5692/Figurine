<?php

// public function = définition d'une fonction sur une instance d'un objet, qui aura accès à $this, et aux valeurs des variables d'instance, ET aux variables statiques.

// public static function = définition d'une fonction sur une classe, qui n'aura pas accès à $this, ni aux valeurs des variables d'instance, mais qui aura toujours accès aux variables statiques.

namespace Figurine\Model;

class Produit
{
    // Méthode pour récupérer tous les produits depuis la base de données
    public static function getAllProduits($db)
    {
        $query = 'SELECT * FROM PRODUIT';
        $stmt = $db->prepare($query); // prepare, évite les injections sql
        $stmt->execute();
        $produits = [];

        // fetch va chercher les infos dans la base de données
        while ($row = $stmt->fetch()) {
            $produits[] = new Produit(
                $row['id_produit'],
                $row['nom'],
                $row['prix'],
                $row['description'],
                $row['taille'],
                $row['image_1'],
                $row['image_2'],
                $row['image_3'],
                $row['date_produit'],
            );
        }

        return $produits;
    }
}
