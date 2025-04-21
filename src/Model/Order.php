<?php

namespace Figurine\Model;

use Figurine\Lib\DbConnector;
use PDO;
use PDOException;

class Order
{
    /**
     * Enregistre une commande dans la base de données.
     * @param int $id_user L'identifiant unique de l'utilisateur.
     * @param array $panier Un tableau contenant les produits du panier (id, quantité, prix).
     * @return int|bool Retourne l'ID de la commande si elle a été enregistrée avec succès, sinon false.
     */
    public function saveOrder($id_user, $panier)
    {
        if (!is_numeric($id_user) || empty($panier)) {
            throw new \InvalidArgumentException('Données invalides pour enregistrer la commande');
        }

        $pdo = DbConnector::dbConnect();

        try {
            // Démarrer une transaction
            $pdo->beginTransaction();

            // Insérer la commande
            $stmt = $pdo->prepare("
                INSERT INTO commande (date_commande, statut, id_utilisateur) 
                VALUES (NOW(), 'en cours', :id_user)
            ");
            $stmt->execute(['id_user' => $id_user]);
            $idOrder = $pdo->lastInsertId();

            // Insérer les produits de la commande
            $stmt = $pdo->prepare("
                INSERT INTO produit_commande (id_commande, id_produit, quantite, prix) 
                VALUES (:id_commande, :id_produit, :quantite, :prix)
            ");
            foreach ($panier as $product) {
                if (!isset($product['id'], $product['quantite'], $product['prix']) || 
                    !is_numeric($product['id']) || 
                    !is_numeric($product['quantite']) || 
                    !is_numeric($product['prix'])) {
                    throw new \InvalidArgumentException('Données de produit invalides dans le panier');
                }

                $stmt->execute([
                    'id_commande' => $idOrder,
                    'id_produit' => $product['id'],
                    'quantite' => $product['quantite'],
                    'prix' => $product['prix']
                ]);
            }

            // Valider la transaction
            $pdo->commit();

            return $idOrder;
        } catch (PDOException $e) {
            // Annuler la transaction en cas d'erreur
            $pdo->rollBack();

            // Log de l'erreur pour déboguer
            error_log('Erreur de base de données dans saveOrder : ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Récupère les commandes d'un utilisateur avec les produits associés.
     * @param int $id_user L'identifiant unique de l'utilisateur.
     * @return array|null Retourne un tableau contenant les commandes ou null en cas d'erreur.
     */
    public function getOrdersByUser($id_user)
    {
        if (!is_numeric($id_user)) {
            throw new \InvalidArgumentException('ID utilisateur invalide pour récupérer les commandes');
        }

        try {
            $pdo = DbConnector::dbConnect();

            // Récupérer les commandes avec les produits associés
            $stmt = $pdo->prepare("
                SELECT 
                    c.id_commande, 
                    c.date_commande, 
                    c.statut, 
                    pc.id_produit, 
                    p.nom AS produit_nom, 
                    pc.quantite, 
                    pc.prix, 
                    p.image_1 AS produit_image
                FROM commande c
                JOIN produit_commande pc ON c.id_commande = pc.id_commande
                JOIN produit p ON pc.id_produit = p.id_produit
                WHERE c.id_utilisateur = :id_user
                ORDER BY c.date_commande DESC
            ");
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Regrouper les produits par commande et calculer le total
            $orders = [];
            foreach ($result as $row) {
                $id_order = $row['id_commande'];

                if (!isset($orders[$id_order])) {
                    $orders[$id_order] = [
                        'id_commande' => $id_order,
                        'date_commande' => $row['date_commande'],
                        'statut' => $row['statut'],
                        'total_commande' => 0,
                        'produits' => []
                    ];
                }

                // Ajouter le produit à la commande
                $orders[$id_order]['produits'][] = [
                    'nom' => $row['produit_nom'],
                    'quantite' => $row['quantite'],
                    'prix' => $row['prix'],
                    'image' => $row['produit_image']
                ];

                // Ajouter au total de la commande
                $orders[$id_order]['total_commande'] += $row['quantite'] * $row['prix'];
            }

            return $orders;
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans getOrdersByUser : ' . $e->getMessage());
            return null;
        }
    }

    
    public function hasOrders($userId)
{
    $pdo = DbConnector::dbConnect();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM commande WHERE id_utilisateur = :id");
    $stmt->execute(['id' => $userId]);
    return ($stmt->fetchColumn() > 0);
}
}