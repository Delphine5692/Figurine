<?php

namespace Figurine\Model;

use Figurine\Lib\DbConnector;
use PDO;
use PDOException;

class Cart
{
    /**
     * Ajoute un produit au panier de l'utilisateur.
     * @param int $id_user L'identifiant unique de l'utilisateur.
     * @param int $id_produit L'identifiant unique du produit.
     * @param int $quantite La quantité à ajouter (par défaut 1).
     * @return bool Retourne true si le produit a été ajouté avec succès, sinon false.
     */
    public function addProduct($id_user, $id_produit, $quantite = 1)
    {
        if (!is_numeric($id_user) || !is_numeric($id_produit) || !is_numeric($quantite) || $quantite <= 0) {
            throw new \InvalidArgumentException('Données invalides pour ajouter un produit au panier');
        }

        try {
            $pdo = DbConnector::dbConnect();
            $stmt = $pdo->prepare("
                INSERT INTO panier (id_utilisateur, id_produit, quantite)
                VALUES (:id_user, :id_produit, :quantite)
                ON DUPLICATE KEY UPDATE quantite = quantite + :quantite
            ");
            return $stmt->execute([
                'id_user' => $id_user,
                'id_produit' => $id_produit,
                'quantite' => $quantite
            ]);
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans addProduct : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère le panier d'un utilisateur.
     * @param int $id_user L'identifiant unique de l'utilisateur.
     * @return array|null Retourne un tableau contenant les produits du panier ou null en cas d'erreur.
     */
    public function getCart($id_user)
    {
        if (!is_numeric($id_user)) {
            throw new \InvalidArgumentException('ID utilisateur invalide pour récupérer le panier');
        }

        try {
            $pdo = DbConnector::dbConnect();
            $stmt = $pdo->prepare("
                SELECT p.id_produit, p.nom, p.prix, pa.quantite
                FROM panier pa
                JOIN produit p ON pa.id_produit = p.id_produit
                WHERE pa.id_utilisateur = :id_user
            ");
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans getCart : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Supprime un produit du panier de l'utilisateur.
     * @param int $id_user L'identifiant unique de l'utilisateur.
     * @param int $id_produit L'identifiant unique du produit à supprimer.
     * @return bool Retourne true si le produit a été supprimé avec succès, sinon false.
     */
    public function removeProduct($id_user, $id_produit)
    {
        if (!is_numeric($id_user) || !is_numeric($id_produit)) {
            throw new \InvalidArgumentException('Données invalides pour supprimer un produit du panier');
        }

        try {
            $pdo = DbConnector::dbConnect();
            $stmt = $pdo->prepare("DELETE FROM panier WHERE id_utilisateur = :id_user AND id_produit = :id_produit");
            return $stmt->execute([
                'id_user' => $id_user,
                'id_produit' => $id_produit
            ]);
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans removeProduct : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Vide le panier de l'utilisateur.
     * @param int $id_user L'identifiant unique de l'utilisateur.
     * @return bool Retourne true si le panier a été vidé avec succès, sinon false.
     */
    public function clearCart($id_user)
    {
        if (!is_numeric($id_user)) {
            throw new \InvalidArgumentException('ID utilisateur invalide pour vider le panier');
        }

        try {
            $pdo = DbConnector::dbConnect();
            $stmt = $pdo->prepare("DELETE FROM panier WHERE id_utilisateur = :id_user");
            return $stmt->execute(['id_user' => $id_user]);
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans clearCart : ' . $e->getMessage());
            return false;
        }
    }
}
