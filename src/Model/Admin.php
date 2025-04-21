<?php

namespace Figurine\Model;

use Figurine\Lib\DbConnector;
use PDO;

class Admin
{
    /**
     * Récupère les statistiques globales pour le tableau de bord admin.
     * @return array Un tableau associatif contenant le nombre total d'utilisateurs, de commentaires et d'articles.
     */
    public function getStatistics()
    {
        try {
            $pdo = DbConnector::dbConnect();

            $stmt = $pdo->query("
                SELECT 
                    (SELECT COUNT(*) FROM utilisateur) AS total_users,
                    (SELECT COUNT(*) FROM commentaire) AS total_comments,
                    (SELECT COUNT(*) FROM article) AS total_articles
            ");
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('Erreur de base de données dans getStatistics : ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Supprime un utilisateur par son ID.
     * @param int $userId L'identifiant unique de l'utilisateur à supprimer.
     * @return bool Retourne true si l'utilisateur a été supprimé avec succès, sinon false.
     */
    public function deleteUser($userId)
    {
        if (!is_numeric($userId)) {
            throw new \InvalidArgumentException('ID utilisateur invalide');
        }

        try {
            $pdo = DbConnector::dbConnect();
            // Démarrage de la transaction
            $pdo->beginTransaction();

            // Suppression des commentaires associés à l'utilisateur
            $stmtComments = $pdo->prepare("DELETE FROM commentaire WHERE id_utilisateur = :id");
            $stmtComments->execute(['id' => $userId]);

            // Suppression de l'utilisateur
            $stmtUser = $pdo->prepare("DELETE FROM utilisateur WHERE id_utilisateur = :id");
            if ($stmtUser->execute(['id' => $userId])) {
                $pdo->commit();
                return true;
            } else {
                error_log('Erreur lors de la suppression de l\'utilisateur : ' . implode(" | ", $stmtUser->errorInfo()));
                $pdo->rollBack();
                return false;
            }
        } catch (\PDOException $e) {
            error_log('Erreur de base de données dans deleteUser : ' . $e->getMessage());
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return false;
        }
    }

}
