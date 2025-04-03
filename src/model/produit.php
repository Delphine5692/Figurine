<?php

namespace Figurine\Model;

use Figurine\Lib\DbConnector;
use PDOException;

class Produit
{
    // Méthode pour récupérer tous les produits depuis la base de données
    public static function getAllProduits()
    {
        try {       // protection de la zone de code où une erreur est possible
            // récupération du connecteur de base en sollicitant la classe singleton
            $db = DbConnector::dbConnect();

            $query = 'SELECT * FROM PRODUIT';
            $stmt = $db->prepare($query);   // prepare, évite les injections sql
            $stmt->execute();

            // fetchAll va chercher les infos (c'est à TOUS les produits) dans la base de données
            $produits = $stmt->fetchAll();
            // $produits contient maintenant un tableau de tableaux ; c'est à dire un tableau des produits
            // sachant que chaque produit est lui-même un tableau associatif.
            // On verra son exploitation dans la partie "vue".

            return $produits;
        }       // fin de la partie protégée (les accès à la base sont terminés... pour le moment !)
        catch (PDOException $e) {
            // pour le moment on affiche "sauvagement" l'erreur ; on verra plus subtil ultérieurement
            echo ($e->getMessage());
            return null;        // pas de produits
        }
    }
}
