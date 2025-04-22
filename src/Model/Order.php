<?php

namespace Figurine\Model;

use Figurine\Lib\DbConnector;
use PDO;
use PDOException;

class Order
{
    /**
     * Enregistre une commande dans la base de données.
     *
     * - Vérifie que l'ID utilisateur est numérique et que le panier n'est pas vide.
     * - Démarre une transaction pour garantir l'exécution de l'insertion de la commande
     *   et l'association de ses produits.
     * - Insère la commande (date, statut, id_utilisateur) dans la table "commande".
     * - Récupère l'ID de la commande insérée et insère chacun des produits du panier dans "produit_commande",
     *   après validation de leurs données (id, quantité, prix).
     * - Valide la transaction si toutes les opérations réussissent, ou l'annule en cas d'erreur.
     *
     * @param int   $id_user L'identifiant unique de l'utilisateur.
     * @param array $panier  Un tableau contenant les produits du panier (chaque élément doit contenir 'id', 'quantite' et 'prix').
     * @return int|bool Retourne l'ID de la commande si l'enregistrement est réussi, sinon false.
     * @throws \InvalidArgumentException Si les données fournies sont invalides.
     */
    public function saveOrder($id_user, $panier)
    {
        if (!is_numeric($id_user) || empty($panier)) {
            throw new \InvalidArgumentException('Données invalides pour enregistrer la commande');
        }

        $pdo = DbConnector::dbConnect();

        try {
            // Démarrer une transaction.
            $pdo->beginTransaction();

            // Insérer la commande dans la table "commande" avec la date actuelle et le statut 'en cours'.
            $stmt = $pdo->prepare("
                INSERT INTO commande (date_commande, statut, id_utilisateur) 
                VALUES (NOW(), 'en cours', :id_user)
            ");
            $stmt->execute(['id_user' => $id_user]);

            // Récupérer l'ID de la commande nouvellement insérée.
            $idOrder = $pdo->lastInsertId();

            // Préparer la requête d'insertion pour chaque produit associé à la commande.
            $stmt = $pdo->prepare("
                INSERT INTO produit_commande (id_commande, id_produit, quantite, prix) 
                VALUES (:id_commande, :id_produit, :quantite, :prix)
            ");
            // Parcourir chaque produit contenu dans le panier.
            foreach ($panier as $product) {
                if (
                    !isset($product['id'], $product['quantite'], $product['prix']) || 
                    !is_numeric($product['id']) || 
                    !is_numeric($product['quantite']) || 
                    !is_numeric($product['prix'])
                ) {
                    throw new \InvalidArgumentException('Données de produit invalides dans le panier');
                }

                // Exécuter la requête d'insertion pour le produit courant.
                $stmt->execute([
                    'id_commande' => $idOrder,
                    'id_produit'  => $product['id'],
                    'quantite'    => $product['quantite'],
                    'prix'        => $product['prix']
                ]);
            }

            // Valider la transaction.
            $pdo->commit();

            return $idOrder;
        } catch (PDOException $e) {
            // En cas d'erreur, annuler la transaction et journaliser l'erreur.
            $pdo->rollBack();
            error_log('Erreur de base de données dans saveOrder : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère les commandes d'un utilisateur avec les produits associés.
     *
     * - Vérifie que l'ID utilisateur est numérique.
     * - Exécute une requête SQL qui joint les tables "commande", "produit_commande" et "produit"
     *   afin d'obtenir pour chaque commande la liste des produits, leurs quantités, prix et images.
     * - Trie les commandes par date décroissante.
     * - Regroupe les produits par commande et calcule le total de chaque commande.
     *
     * @param int $id_user L'identifiant unique de l'utilisateur.
     * @return array|null Retourne un tableau associatif regroupant les commandes ou null en cas d'erreur.
     * @throws \InvalidArgumentException Si l'ID utilisateur est invalide.
     */
    public function getOrdersByUser($id_user)
    {
        if (!is_numeric($id_user)) {
            throw new \InvalidArgumentException('ID utilisateur invalide pour récupérer les commandes');
        }

        try {
            $pdo = DbConnector::dbConnect();

            // Préparer la requête SQL pour récupérer les commandes et les détails des produits associés.
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

            // Regrouper les produits par commande et calculer le total de la commande.
            $orders = [];
            foreach ($result as $row) {
                $id_order = $row['id_commande'];

                if (!isset($orders[$id_order])) {
                    $orders[$id_order] = [
                        'id_commande'    => $id_order,
                        'date_commande'  => $row['date_commande'],
                        'statut'         => $row['statut'],
                        'total_commande' => 0,
                        'produits'       => []
                    ];
                }

                // Ajouter le produit à la commande.
                $orders[$id_order]['produits'][] = [
                    'nom'       => $row['produit_nom'],
                    'quantite'  => $row['quantite'],
                    'prix'      => $row['prix'],
                    'image'     => $row['produit_image']
                ];

                // Calculer le total de la commande.
                $orders[$id_order]['total_commande'] += $row['quantite'] * $row['prix'];
            }

            return $orders;
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans getOrdersByUser : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Vérifie si un utilisateur a déjà des commandes enregistrées.
     *
     * - Exécute une requête SQL qui compte le nombre de commandes associées à l'utilisateur.
     *
     * @param int $userId L'identifiant unique de l'utilisateur.
     * @return bool Retourne true si l'utilisateur a au moins une commande, sinon false.
     */
    public function hasOrders($userId)
    {
        $pdo = DbConnector::dbConnect();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM commande WHERE id_utilisateur = :id");
        $stmt->execute(['id' => $userId]);
        return ($stmt->fetchColumn() > 0);
    }
}
?>