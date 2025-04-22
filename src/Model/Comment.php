<?php

namespace Figurine\Model;

use Figurine\Lib\DbConnector;
use PDO;
use PDOException;

class Comment
{
    /**
     * Ajoute un commentaire dans la base de données.
     *
     * - Vérifie que le contenu du commentaire n'est pas vide et que les IDs article et utilisateur sont numériques.
     * - Se connecte à la base via DbConnector.
     * - Prépare et exécute une requête d'insertion.
     *
     * @param string $msg_blog Le contenu du commentaire.
     * @param int $id_article L'identifiant unique de l'article associé.
     * @param int $id_user L'identifiant unique de l'utilisateur qui ajoute le commentaire.
     * @return bool Retourne true si le commentaire a été inséré avec succès, sinon false.
     * @throws \InvalidArgumentException Si les données fournies sont invalides.
     */
    public function addComment($msg_blog, $id_article, $id_user)
    {
        if (empty($msg_blog) || !is_numeric($id_article) || !is_numeric($id_user)) {
            throw new \InvalidArgumentException('Données invalides pour ajouter un commentaire');
        }

        try {
            $pdo = DbConnector::dbConnect();
            $stmt = $pdo->prepare("
                INSERT INTO commentaire (msg_blog, id_article, id_utilisateur) 
                VALUES (:msg_blog, :id_article, :id_user)
            ");
            return $stmt->execute([
                'msg_blog'    => $msg_blog,
                'id_article'  => $id_article,
                'id_user'     => $id_user
            ]);
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans addComment : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère les commentaires associés à un article.
     *
     * - Valide que l'ID de l'article est numérique.
     * - Effectue une jointure entre les tables commentaire et utilisateur pour récupérer
     *   les informations de l'auteur (nom et prénom) et filtre selon le statut 'actif'.
     * - Trie les commentaires par date décroissante.
     *
     * @param int $id_article L'identifiant unique de l'article.
     * @return array|null Un tableau de commentaires ou null en cas d'erreur.
     * @throws \InvalidArgumentException Si l'ID de l'article n'est pas valide.
     */
    public function getComments($id_article)
    {
        if (!is_numeric($id_article)) {
            throw new \InvalidArgumentException('ID d\'article invalide pour récupérer les commentaires');
        }

        try {
            $pdo = DbConnector::dbConnect();
            $stmt = $pdo->prepare("
                SELECT c.msg_blog, c.date_commentaire, u.nom, u.prenom 
                FROM commentaire c
                JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
                WHERE c.id_article = :id_article AND u.statut = 'actif'
                ORDER BY c.date_commentaire DESC
            ");
            $stmt->bindParam(':id_article', $id_article, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans getComments : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupère les commentaires d'un utilisateur spécifique.
     *
     * - Valide que l'ID de l'utilisateur est numérique.
     * - Joint les tables commentaire et article pour récupérer, en plus du contenu et de la date,
     *   le titre de l'article associé.
     * - Trie les résultats par date décroissante.
     *
     * @param int $id_user L'identifiant unique de l'utilisateur.
     * @return array|null Un tableau de commentaires ou null en cas d'erreur.
     * @throws \InvalidArgumentException Si l'ID utilisateur n'est pas valide.
     */
    public function getCommentsByUser($id_user)
    {
        if (!is_numeric($id_user)) {
            throw new \InvalidArgumentException('ID utilisateur invalide pour récupérer les commentaires');
        }

        try {
            $pdo = DbConnector::dbConnect();
            $stmt = $pdo->prepare("
                SELECT c.id_commentaire, c.msg_blog, c.date_commentaire, a.titre 
                FROM commentaire c
                JOIN article a ON c.id_article = a.id_article
                WHERE c.id_utilisateur = :id_user
                ORDER BY c.date_commentaire DESC
            ");
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans getCommentsByUser : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Vérifie si un utilisateur est le propriétaire d'un commentaire.
     *
     * - Valide que les IDs du commentaire et de l'utilisateur sont numériques.
     * - Exécute une requête qui retourne 1 si le commentaire appartient à l'utilisateur.
     *
     * @param int $id_commentaire L'identifiant du commentaire.
     * @param int $id_user L'identifiant de l'utilisateur.
     * @return bool Retourne true si l'utilisateur est le propriétaire, sinon false.
     * @throws \InvalidArgumentException Si les données fournies sont invalides.
     */
    public function verifyCommentOwner($id_commentaire, $id_user)
    {
        if (!is_numeric($id_commentaire) || !is_numeric($id_user)) {
            throw new \InvalidArgumentException('Données invalides pour vérifier le propriétaire du commentaire');
        }

        try {
            $pdo = DbConnector::dbConnect();
            $stmt = $pdo->prepare("
                SELECT 1 
                FROM commentaire 
                WHERE id_commentaire = :id_commentaire AND id_utilisateur = :id_user
            ");
            $stmt->execute([
                'id_commentaire' => $id_commentaire,
                'id_user'        => $id_user
            ]);

            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans verifyCommentOwner : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime un commentaire par son ID.
     *
     * - Valide que l'ID du commentaire est numérique.
     * - Exécute une requête DELETE pour retirer le commentaire de la base.
     *
     * @param int $id_commentaire L'identifiant du commentaire à supprimer.
     * @return bool Retourne true si la suppression est effectuée, sinon false.
     * @throws \InvalidArgumentException Si l'ID du commentaire n'est pas valide.
     */
    public function deleteComment($id_commentaire)
    {
        if (!is_numeric($id_commentaire)) {
            throw new \InvalidArgumentException('ID commentaire invalide pour suppression');
        }

        try {
            $pdo = DbConnector::dbConnect();
            $stmt = $pdo->prepare("DELETE FROM commentaire WHERE id_commentaire = :id_commentaire");
            return $stmt->execute(['id_commentaire' => $id_commentaire]);
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans deleteComment : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère tous les commentaires.
     *
     * - Exécute une requête qui joint les tables commentaire, utilisateur et article afin
     *   de récupérer pour chaque commentaire le nom de l'utilisateur et le titre de l'article associé.
     * - Trie les commentaires par date décroissante.
     *
     * @return array|null Un tableau contenant tous les commentaires ou null en cas d'erreur.
     */
    public function getAllComments()
    {
        try {
            $pdo = DbConnector::dbConnect();
            $stmt = $pdo->query("
                SELECT c.id_commentaire, c.msg_blog, c.date_commentaire, u.nom AS utilisateur, a.titre AS article
                FROM commentaire c
                JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
                JOIN article a ON c.id_article = a.id_article
                ORDER BY c.date_commentaire DESC
            ");

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans getAllComments : ' . $e->getMessage());
            return null;
        }
    }
}
?>