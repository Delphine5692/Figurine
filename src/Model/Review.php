<?php

namespace Figurine\Model;

use Figurine\Lib\DbConnector;
use PDO;
use PDOException;

/**
 * Cette classe gère les opérations liées aux avis sur les produits :
 * - L'insertion d'un nouvel avis.
 * - La récupération des avis d'un produit.
 * - La récupération des avis d'un utilisateur.
 * - La suppression d'un avis.
 * - La récupération du dernier avis d'un produit.
 */
class Review
{
    // Propriété pour stocker l'instance de connexion PDO.
    private $db;

    /**
     * Constructeur
     *
     * Initialise la connexion à la base de données via DbConnector.
     */
    public function __construct()
    {
        $this->db = DbConnector::dbConnect();
    }

    /**
     * Insère un nouvel avis dans la base de données.
     *
     * @param int    $id_produit    L'ID du produit concerné.
     * @param int    $id_utilisateur L'ID de l'utilisateur qui ajoute l'avis.
     * @param string $msg_avis       Le contenu de l'avis.
     * @return bool Retourne true si l'insertion est réussie, sinon false.
     */
    public function addReview($id_produit, $id_utilisateur, $msg_avis)
    {
        try {
            // Prépare la requête d'insertion avec NOW() pour la date d'avis.
            $stmt = $this->db->prepare("INSERT INTO avis (msg_avis, id_produit, id_utilisateur, date_avis) VALUES (?, ?, ?, NOW())");
            return $stmt->execute([$msg_avis, $id_produit, $id_utilisateur]);
        } catch (PDOException $e) {
            // En cas d'erreur, journalise le message et retourne false.
            error_log('Erreur dans addReview: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère les avis d'un produit.
     *
     * Exécute une requête qui joint les tables avis et utilisateur pour obtenir
     * le contenu de l'avis, la date et le nom de l'auteur, triés par date décroissante.
     *
     * @param int $id_produit L'ID du produit.
     * @return array Retourne un tableau associatif contenant les avis, ou un tableau vide en cas d'erreur.
     */
    public function getReviewsByProduct($id_produit)
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT a.msg_avis AS contenu, a.date_avis AS date_review, u.nom AS auteur 
                 FROM avis a 
                 JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur 
                 WHERE a.id_produit = ? 
                 ORDER BY a.date_avis DESC"
            );
            $stmt->execute([$id_produit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Si une erreur survient, log l'erreur et retourne un tableau vide.
            error_log('Erreur dans getReviewsByProduct: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère les avis d'un utilisateur.
     *
     * Joint la table avis et la table produit pour récupérer, pour chaque avis,
     * le contenu, la date et le nom du produit associé.
     *
     * @param int $id_utilisateur L'ID de l'utilisateur.
     * @return array Retourne un tableau associatif des avis ou un tableau vide en cas d'erreur.
     */
    public function getReviewsByUser($id_utilisateur)
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT r.id_avis, r.msg_avis AS contenu, r.date_avis AS date_review, p.nom 
                 FROM avis r 
                 JOIN produit p ON r.id_produit = p.id_produit 
                 WHERE r.id_utilisateur = ? 
                 ORDER BY r.date_avis DESC"
            );
            $stmt->execute([$id_utilisateur]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur dans getReviewsByUser: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Supprime un avis par son ID.
     *
     * Exécute une requête DELETE pour retirer l'avis correspondant à l'ID fourni.
     *
     * @param int $id_avis L'identifiant de l'avis à supprimer.
     * @return bool Retourne true si la suppression est effectuée, sinon false.
     */
    public function deleteReview($id_avis)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM avis WHERE id_avis = ?");
            return $stmt->execute([$id_avis]);
        } catch (PDOException $e) {
            error_log('Erreur dans deleteReview: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère le dernier avis d'un produit.
     *
     * Exécute une requête pour obtenir l'avis le plus récent d'un produit donné,
     * en jointurant avec la table utilisateur pour obtenir le nom de l'auteur.
     *
     * @param int $id_produit L'identifiant du produit.
     * @return array|null Retourne un tableau associatif représentant le dernier avis ou null en cas d'erreur.
     */
    public function getLastReviewByProduct($id_produit)
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT r.id_avis, r.msg_avis AS contenu, r.date_avis AS date_review, u.nom AS auteur 
                 FROM avis r 
                 JOIN utilisateur u ON r.id_utilisateur = u.id_utilisateur 
                 WHERE r.id_produit = ? 
                 ORDER BY r.date_avis DESC
                 LIMIT 1"
            );
            $stmt->execute([$id_produit]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur dans getLastReviewByProduct: ' . $e->getMessage());
            return null;
        }
    }
}
?>