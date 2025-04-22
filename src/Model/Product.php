<?php

namespace Figurine\Model;

use Figurine\Lib\DbConnector;
use PDO;
use PDOException;

class Product
{
    // Propriété pour stocker l'instance PDO pour la connexion à la base.
    private $db;

    /**
     * Constructeur qui initialise la connexion à la base de données.
     */
    public function __construct()
    {
        $this->db = DbConnector::dbConnect();
    }

    /**
     * Récupère tous les produits depuis la base de données.
     *
     * Utilise une requête SQL pour sélectionner l'ensemble des produits présents dans la table "produit",
     * triés par date de publication décroissante.
     *
     * @return array|null Retourne un tableau associatif contenant tous les produits ou null en cas d'erreur.
     */
    public static function getAllProducts()
    {
        try {
            $db = DbConnector::dbConnect();

            $query = 'SELECT * FROM produit ORDER BY date_produit DESC';
            $stmt = $db->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans getAllProducts : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupère les derniers produits ajoutés.
     *
     * Cette méthode récupère une quantité limitée de produits récents par ordre décroissant de date
     * de publication.
     *
     * @param int $limit Le nombre maximum de produits à récupérer.
     * @return array|null Retourne un tableau associatif des derniers produits ou null en cas d'erreur.
     * @throws \InvalidArgumentException Si la valeur de $limit n'est pas un nombre positif.
     */
    public function getLastProducts($limit)
    {
        if (!is_numeric($limit) || $limit <= 0) {
            throw new \InvalidArgumentException('Limite invalide pour récupérer les derniers produits');
        }

        try {
            $pdo = DbConnector::dbConnect();
            $stmt = $pdo->prepare("SELECT id_produit, nom, image_1, prix FROM produit ORDER BY date_produit DESC LIMIT :limit");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans getLastProducts : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupère un produit par son ID.
     *
     * Valide l'ID du produit puis exécute une requête préparée afin de récupérer
     * les informations correspondantes depuis la table "produit".
     *
     * @param int $id_produit L'identifiant unique du produit.
     * @return array|null Retourne un tableau associatif avec les informations du produit ou null en cas d'erreur.
     * @throws \InvalidArgumentException Si l'ID du produit n'est pas valide.
     */
    public function getProductById($id_produit)
    {
        if (!is_numeric($id_produit)) {
            throw new \InvalidArgumentException('ID produit invalide');
        }

        try {
            $pdo = DbConnector::dbConnect();
            $stmt = $pdo->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
            $stmt->bindParam(':id_produit', $id_produit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans getProductById : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupère les quatre derniers produits ajoutés.
     *
     * Exécute une requête pour récupérer les quatre produits les plus récents de la table "produit".
     *
     * @return array|null Retourne un tableau associatif contenant les quatre derniers produits ou null en cas d'erreur.
     */
    public static function getLastFourProducts()
    {
        try {
            $db = DbConnector::dbConnect();
            $query = 'SELECT id_produit, nom, image_1, prix FROM produit ORDER BY date_produit DESC LIMIT 4';
            $stmt = $db->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans getLastFourProducts : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Ajoute un nouveau produit dans la base de données.
     *
     * Insère un produit dans la table "produit" en enregistrant le nom, la description, le prix,
     * la taille, le nom du fichier de l'image et la date de publication (définie à NOW()).
     *
     * @param string $nom Le nom du produit.
     * @param string $description La description du produit.
     * @param string $prix Le prix du produit.
     * @param string $taille La taille du produit.
     * @param string $imagePath Le nom du fichier de l'image associée.
     * @return bool|int Retourne l'ID du produit inséré en cas de succès, sinon false.
     */
    public function addProduct($nom, $description, $prix, $taille, $imagePath)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO produit (nom, description, prix, taille, image_1, date_produit) VALUES (?, ?, ?, ?, ?, NOW())");
            if (!$stmt->execute([$nom, $description, $prix, $taille, $imagePath])) {
                $errorInfo = $stmt->errorInfo();
                error_log('Erreur dans addProduct: ' . implode(', ', $errorInfo));
                return false;
            }
            return $this->db->lastInsertId(); // Retourne l'ID du produit inséré.
        } catch (PDOException $e) {
            error_log('Erreur dans addProduct: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime un produit de la base de données.
     *
     * Valide l'ID du produit puis exécute une requête de suppression dans la table "produit".
     *
     * @param int $id_product L'identifiant unique du produit à supprimer.
     * @return bool Retourne true si la suppression est effectuée avec succès, sinon false.
     * @throws \InvalidArgumentException Si l'ID du produit est invalide.
     */
    public function deleteProduct($id_product)
    {
        if (!is_numeric($id_product)) {
            throw new \InvalidArgumentException('ID produit invalide pour la suppression');
        }

        try {
            $stmt = $this->db->prepare("DELETE FROM produit WHERE id_produit = ?");
            return $stmt->execute([$id_product]);
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans deleteProduct : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère le produit mis en avant.
     *
     * Retourne le produit possédant le plus grand nombre d'avis, déterminé par une jointure entre "produit" et "avis".
     *
     * @return array|null Retourne un tableau associatif représentant le produit mis en avant ou null en cas d'erreur.
     */
    public static function getFeaturedProduct()
    {
        try {
            $db = DbConnector::dbConnect();
            $query = "
                SELECT p.*, COUNT(a.id_avis) AS review_count
                FROM produit p
                LEFT JOIN avis a ON p.id_produit = a.id_produit
                GROUP BY p.id_produit
                ORDER BY review_count DESC
                LIMIT 1
            ";
            $stmt = $db->prepare($query);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('Erreur dans getFeaturedProduct: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupère les produits appartenant à une catégorie donnée.
     *
     * Cette méthode utilise une sous-requête pour récupérer les produits associés à l'identifiant de la catégorie fourni,
     * et les trie par date de publication décroissante.
     *
     * @param int $id_category L'identifiant de la catégorie.
     * @return array Retourne un tableau associatif contenant les produits ou un tableau vide en cas d'erreur.
     */
    public static function getProductsByCategory($id_category)
    {
        try {
            $db = DbConnector::dbConnect();
            // La sous-requête récupère les produits associés à la catégorie
            $query = "
                SELECT *
                FROM produit
                WHERE id_produit IN (
                    SELECT id_produit FROM produit_categorie WHERE id_categorie = ?
                )
                ORDER BY date_produit DESC
            ";
            $stmt = $db->prepare($query);
            $stmt->execute([$id_category]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur dans getProductsByCategory: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Associe un produit à plusieurs catégories.
     *
     * Pour chaque identifiant de catégorie contenu dans le tableau, insère une ligne dans la table de liaison "produit_categorie"
     * qui associe le produit à la catégorie.
     *
     * @param int $id_product L'identifiant du produit.
     * @param array $categories Tableau des identifiants de catégories.
     * @return bool Retourne true en cas de succès, sinon false.
     */
    public function addCategoriesToProduct($id_product, array $categories)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO produit_categorie (id_produit, id_categorie) VALUES (?, ?)");
            foreach ($categories as $id_category) {
                $stmt->execute([$id_product, $id_category]);
            }
            return true;
        } catch (PDOException $e) {
            error_log('Erreur dans addCategoriesToProduct: ' . $e->getMessage());
            return false;
        }
    }
}
?>