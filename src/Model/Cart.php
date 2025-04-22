<?php

namespace Figurine\Model;

use Figurine\Lib\DbConnector;
use PDO;
use PDOException;

class Cart
{
    /**
     * Ajoute un produit au panier de l'utilisateur.
     *
     * - Vérifie que l'ID utilisateur, l'ID du produit et la quantité sont des valeurs numériques et que
     *   la quantité est supérieure à zéro.
     * - Utilise une requête SQL avec "ON DUPLICATE KEY UPDATE" pour incrémenter la quantité
     *   si le produit existe déjà dans le panier.
     *
     * @param int $id_user L'identifiant unique de l'utilisateur.
     * @param int $id_produit L'identifiant unique du produit.
     * @param int $quantite La quantité à ajouter (par défaut 1).
     * @return bool Retourne true si le produit a été ajouté avec succès, sinon false.
     * @throws \InvalidArgumentException Si les paramètres ne sont pas valides.
     */
    public function addProduct($id_user, $id_produit, $quantite = 1)
    {
        // Vérifie que les paramètres sont numériques et que la quantité est positive.
        if (!is_numeric($id_user) || !is_numeric($id_produit) || !is_numeric($quantite) || $quantite <= 0) {
            throw new \InvalidArgumentException('Données invalides pour ajouter un produit au panier');
        }

        try {
            // Connexion à la base de données via DbConnector.
            $pdo = DbConnector::dbConnect();
            // Préparation de la requête d'insertion, ou mise à jour si le produit existe déjà.
            $stmt = $pdo->prepare("
                INSERT INTO panier (id_utilisateur, id_produit, quantite)
                VALUES (:id_user, :id_produit, :quantite)
                ON DUPLICATE KEY UPDATE quantite = quantite + :quantite
            ");
            // Exécution de la requête avec les paramètres fournis.
            return $stmt->execute([
                'id_user'    => $id_user,
                'id_produit' => $id_produit,
                'quantite'   => $quantite
            ]);
        } catch (PDOException $e) {
            // En cas d'erreur, journalise le message d'erreur et retourne false.
            error_log('Erreur de base de données dans addProduct : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère le panier d'un utilisateur.
     *
     * - Vérifie que l'ID utilisateur est numérique.
     * - Effectue une jointure entre les tables "panier" et "produit" pour récupérer
     *   les informations détaillées (nom, prix) des produits présents dans le panier.
     *
     * @param int $id_user L'identifiant unique de l'utilisateur.
     * @return array|null Retourne un tableau contenant les produits du panier ou null en cas d'erreur.
     * @throws \InvalidArgumentException Si l'ID utilisateur n'est pas valide.
     */
    public function getCart($id_user)
    {
        if (!is_numeric($id_user)) {
            throw new \InvalidArgumentException('ID utilisateur invalide pour récupérer le panier');
        }

        try {
            // Connexion à la base de données.
            $pdo = DbConnector::dbConnect();
            // Préparation de la requête SQL qui sélectionne les produits du panier
            // avec leurs informations (id_produit, nom, prix) et la quantité associée.
            $stmt = $pdo->prepare("
                SELECT p.id_produit, p.nom, p.prix, pa.quantite
                FROM panier pa
                JOIN produit p ON pa.id_produit = p.id_produit
                WHERE pa.id_utilisateur = :id_user
            ");
            // Lien du paramètre id_user.
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $stmt->execute();

            // Retourne tous les résultats sous forme de tableau associatif.
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans getCart : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Supprime un produit du panier de l'utilisateur.
     *
     * - Vérifie que les identifiants utilisateur et produit sont numériques.
     * - Exécute une requête DELETE pour retirer le produit du panier.
     *
     * @param int $id_user L'identifiant unique de l'utilisateur.
     * @param int $id_produit L'identifiant unique du produit à supprimer.
     * @return bool Retourne true si le produit a été supprimé avec succès, sinon false.
     * @throws \InvalidArgumentException Si les paramètres ne sont pas valides.
     */
    public function removeProduct($id_user, $id_produit)
    {
        if (!is_numeric($id_user) || !is_numeric($id_produit)) {
            throw new \InvalidArgumentException('Données invalides pour supprimer un produit du panier');
        }

        try {
            // Connexion à la base.
            $pdo = DbConnector::dbConnect();
            // Préparation de la requête DELETE pour le produit spécifié.
            $stmt = $pdo->prepare("DELETE FROM panier WHERE id_utilisateur = :id_user AND id_produit = :id_produit");
            // Exécution de la suppression en passant les identifiants nécessaires.
            return $stmt->execute([
                'id_user'    => $id_user,
                'id_produit' => $id_produit
            ]);
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans removeProduct : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Vide le panier de l'utilisateur.
     *
     * - Vérifie que l'ID utilisateur est numérique.
     * - Exécute une requête DELETE pour supprimer tous les produits du panier de l'utilisateur.
     *
     * @param int $id_user L'identifiant unique de l'utilisateur.
     * @return bool Retourne true si le panier a été vidé avec succès, sinon false.
     * @throws \InvalidArgumentException Si l'ID utilisateur n'est pas valide.
     */
    public function clearCart($id_user)
    {
        if (!is_numeric($id_user)) {
            throw new \InvalidArgumentException('ID utilisateur invalide pour vider le panier');
        }

        try {
            // Obtention de la connexion PDO.
            $pdo = DbConnector::dbConnect();
            // Préparation de la requête DELETE pour vider le panier de l'utilisateur.
            $stmt = $pdo->prepare("DELETE FROM panier WHERE id_utilisateur = :id_user");
            // Exécution de la requête avec le paramètre id_user.
            return $stmt->execute(['id_user' => $id_user]);
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans clearCart : ' . $e->getMessage());
            return false;
        }
    }
}
?>