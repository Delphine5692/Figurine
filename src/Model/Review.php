<?php

namespace Figurine\Model;

use Figurine\Lib\DbConnector;
use PDO;
use PDOException;

class Review
{
    private $db;

    public function __construct()
    {
        $this->db = DbConnector::dbConnect();
    }

    /**
     * Insère un nouvel avis dans la base de données.
     *
     * @param int $id_produit L'ID du produit.
     * @param int $id_utilisateur L'ID de l'utilisateur.
     * @param string $msg_avis Le contenu de l'avis.
     * @return bool Retourne true si l'insertion est réussie, sinon false.
     */
    public function addReview($id_produit, $id_utilisateur, $msg_avis)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO avis (msg_avis, id_produit, id_utilisateur, date_avis) VALUES (?, ?, ?, NOW())");
            return $stmt->execute([$msg_avis, $id_produit, $id_utilisateur]);
        } catch (PDOException $e) {
            error_log('Erreur dans addReview: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère les avis d'un produit.
     *
     * @param int $id_produit L'ID du produit.
     * @return array Retourne un tableau d'avis.
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
            error_log('Erreur dans getReviewsByProduct: ' . $e->getMessage());
            return [];
        }
    }

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
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
        error_log('Erreur dans getLastReviewByProduct: ' . $e->getMessage());
        return null;
    }
}

}