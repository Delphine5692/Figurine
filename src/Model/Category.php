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
     * - Établit une connexion à la base de données via DbConnector.
     * - Exécute une requête SQL qui récupère toutes les catégories,
     *   en effectuant des jointures avec les tables produi_categorie et produit.
     * - Calcule la date de publication la plus récente pour les produits associés à chaque catégorie
     *   via l'agrégation (MAX(p.date_produit)).
     * - Trie les résultats en fonction de cette date (du plus récent au plus ancien).
     * - Retourne un tableau associatif de catégories.
     *
     * En cas d'erreur, l'erreur est journalisée et un tableau vide est retourné.
     *
     * @return array Un tableau associatif contenant les catégories et la date du dernier produit.
     */
    public static function getAllCategories()
    {
        try {
            // Obtention de la connexion PDO via DbConnector.
            $db = DbConnector::dbConnect();

            // Définition de la requête SQL qui récupère les catégories avec
            // la date du dernier produit associé, triées par date décroissante.
            $query = "
                SELECT c.*, MAX(p.date_produit) AS last_product_date
                FROM categorie c
                LEFT JOIN produit_categorie pc ON c.id_categorie = pc.id_categorie
                LEFT JOIN produit p ON pc.id_produit = p.id_produit
                GROUP BY c.id_categorie
                ORDER BY last_product_date DESC
            ";

            // Préparation et exécution de la requête SQL.
            $stmt = $db->prepare($query);
            $stmt->execute();

            // Retourne l'ensemble des enregistrements sous forme de tableau associatif.
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // En cas d'erreur, journalise le message et retourne un tableau vide.
            error_log('Erreur dans getAllCategories: ' . $e->getMessage());
            return [];
        }
    }
}
?>