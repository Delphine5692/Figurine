<?php

namespace Figurine\Model;

use Figurine\Lib\DbConnector;
use PDO;

class Admin
{
    /**
     * Récupère les statistiques globales pour le tableau de bord admin.
     *
     * - Se connecte à la base de données via DbConnector.
     * - Exécute une requête SQL qui effectue trois sous-sélections pour compter le nombre total
     *   d'utilisateurs, de commentaires et d'articles.
     * - En cas de succès, retourne le résultat sous forme d'un tableau associatif.
     * - En cas d'erreur, log le message d'erreur et retourne un tableau vide.
     *
     * @return array Un tableau associatif contenant 'total_users', 'total_comments' et 'total_articles'.
     */
    public function getStatistics()
    {
        try {
            // Obtention de la connexion PDO
            $pdo = DbConnector::dbConnect();

            // Exécute la requête de comptage sur les tables utilisateur, commentaire et article.
            $stmt = $pdo->query("
                SELECT 
                    (SELECT COUNT(*) FROM utilisateur) AS total_users,
                    (SELECT COUNT(*) FROM commentaire) AS total_comments,
                    (SELECT COUNT(*) FROM article) AS total_articles
            ");
            // Retourne les résultats sous forme de tableau associatif.
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('Erreur de base de données dans getStatistics : ' . $e->getMessage());
            // Retourne un tableau vide en cas d'échec.
            return [];
        }
    }

    /**
     * Supprime un utilisateur par son ID.
     *
     * - Vérifie que l'ID utilisateur est valide (numérique).
     * - Se connecte à la base de données.
     * - Démarre afin de garantir que toutes les suppressions (commentaires et utilisateur)
     *   soient traitées.
     * - Supprime d'abord tous les commentaires associés à l'utilisateur.
     * - Ensuite, tente de supprimer l'utilisateur.
     * - Si la suppression de l'utilisateur aboutit, valide et retourne true.
     * - Sinon, annule, logue l'erreur et retourne false.
     * - Gère également les exceptions et annule en cas d'erreur.
     *
     * @param int $userId L'identifiant unique de l'utilisateur à supprimer.
     * @return bool Retourne true en cas de succès, sinon false.
     * @throws \InvalidArgumentException Si l'ID utilisateur fourni n'est pas numérique.
     */
    public function deleteUser($userId)
    {
        // Vérifie que l'identifiant utilisateur est bien un nombre.
        if (!is_numeric($userId)) {
            throw new \InvalidArgumentException('ID utilisateur invalide');
        }

        try {
            $pdo = DbConnector::dbConnect();
            $pdo->beginTransaction();

            // Prépare et exécute la suppression des commentaires liés à cet utilisateur.
            $stmtComments = $pdo->prepare("DELETE FROM commentaire WHERE id_utilisateur = :id");
            $stmtComments->execute(['id' => $userId]);

            // Prépare et exécute la suppression de l'utilisateur.
            $stmtUser = $pdo->prepare("DELETE FROM utilisateur WHERE id_utilisateur = :id");
            if ($stmtUser->execute(['id' => $userId])) {
                // Si la suppression de l'utilisateur est réussie, valide.
                $pdo->commit();
                return true;
            } else {
                // En cas d'échec, log les erreurs retournées par PDO et annule.
                error_log('Erreur lors de la suppression de l\'utilisateur : ' . implode(" | ", $stmtUser->errorInfo()));
                $pdo->rollBack();
                return false;
            }
        } catch (\PDOException $e) {
            // En cas d'exception, log l'erreur et annule si elle est active.
            error_log('Erreur de base de données dans deleteUser : ' . $e->getMessage());
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return false;
        }
    }
}