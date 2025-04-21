<?php

namespace Figurine\Model;

use Figurine\Lib\DbConnector;
use PDO;
use PDOException;

class Article extends BaseModel
{
    /**
     * Récupère tous les articles triés par date de publication décroissante.
     * @return array|null Retourne un tableau contenant tous les articles ou null en cas d'erreur.
     */
    public static function getAllArticles()
    {
        // Utilisation de la méthode statique getDb() pour obtenir la connexion PDO
        $db = self::getDb();

        $query = 'SELECT * FROM article ORDER BY date_publication DESC';
        $result = self::executeQuery($query);
        // executeQuery retourne un tableau associatif pour une requête SELECT  
        return is_array($result) ? $result : null;
    }

    /**
     * Récupère un article par son ID.
     * @param int $id L'identifiant unique de l'article.
     * @return array|null Retourne un tableau contenant les informations de l'article ou null en cas d'erreur.
     */
    public static function getArticleById($id)
    {
        if (!is_numeric($id)) {
            throw new \InvalidArgumentException('ID d\'article invalide');
        }

        try {
            $db = DbConnector::dbConnect();

            $query = 'SELECT * FROM article WHERE id_article = :id';
            $stmt = $db->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans getArticleById : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupère les 3 derniers articles triés par date de publication décroissante.
     * @return array|null Retourne un tableau contenant les 3 derniers articles ou null en cas d'erreur.
     */
    public static function getLastThreeArticles()
    {
        try {
            $db = DbConnector::dbConnect();

            $query = 'SELECT * FROM article ORDER BY date_publication DESC LIMIT 3';
            $stmt = $db->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans getLastThreeArticles : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupère les commentaires d'un article spécifique.
     * @param int $id_article L'identifiant unique de l'article.
     * @return array|null Retourne un tableau contenant les commentaires ou null en cas d'erreur.
     */
    public static function getCommentsByArticle($id_article)
    {
        if (!is_numeric($id_article)) {
            throw new \InvalidArgumentException('ID d\'article invalide pour les commentaires');
        }

        try {
            $db = DbConnector::dbConnect();

            $query = 'SELECT c.msg_blog, c.date_commentaire, u.nom, u.prenom 
                      FROM commentaire c
                      INNER JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
                      WHERE c.id_article = :id_article 
                      ORDER BY c.date_commentaire DESC';
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id_article', $id_article, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans getCommentsByArticle : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Ajoute un nouvel article dans la base de données.
     * @param string $title Le titre de l'article.
     * @param string $description La description de l'article.
     * @param string $imagePath Le chemin de l'image associée à l'article.
     * @return bool Retourne true si l'article a été ajouté avec succès, sinon false.
     */
    public function addArticle($title, $description, $imagePath)
    {
        // Validation des données
        if (empty($title) || empty($description) || empty($imagePath)) {
            throw new \InvalidArgumentException('Données de l\'article invalides');
        }

        // Valider le type de fichier image
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $fileExtension = pathinfo($imagePath, PATHINFO_EXTENSION);

        if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
            throw new \InvalidArgumentException('Type de fichier image invalide');
        }

        try {
            $pdo = DbConnector::dbConnect();

            $stmt = $pdo->prepare("
                INSERT INTO article (titre, description, image, date_publication) 
                VALUES (:titre, :description, :image, NOW())
            ");
            return $stmt->execute([
                'titre' => $title,
                'description' => $description,
                'image' => $imagePath
            ]);
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans addArticle : ' . $e->getMessage());
            return false;
        }
    }
}
