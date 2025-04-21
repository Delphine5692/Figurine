<?php

namespace Figurine\Model;

use Figurine\Lib\DbConnector;
use PDO;
use PDOException;

class Category
{
    /**
     * Récupère toutes les catégories.
     *
     * @return array
     */
    public static function getAllCategories()
    {
        try {
            $db = DbConnector::dbConnect();
            $query = "
                SELECT c.*, MAX(p.date_produit) AS last_product_date
                FROM categorie c
                LEFT JOIN produit_categorie pc ON c.id_categorie = pc.id_categorie
                LEFT JOIN produit p ON pc.id_produit = p.id_produit
                GROUP BY c.id_categorie
                ORDER BY last_product_date DESC
            ";
            $stmt = $db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur dans getAllCategories: ' . $e->getMessage());
            return [];
        }
    }
}