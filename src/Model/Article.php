<?php

namespace Figurine\Model;

use Figurine\Lib\DbConnector;
use Figurine\Model\BaseModel;
use PDO;
use PDOException;

class Article extends BaseModel
{
    /**
     * Récupère tous les articles triés par date de publication décroissante.
     *
     * - Utilise la connexion PDO obtenue via la méthode statique getDb().
     * - Exécute une requête SQL pour sélectionner tous les articles.
     * - Trie les articles par date de publication du plus récent au plus ancien.
     *
     * @return array|null Retourne un tableau contenant tous les articles ou null en cas d'erreur.
     */
    public static function getAllArticles()
    {
        // Récupération de la connexion PDO via la méthode statique hébergée dans BaseModel.
        $db = self::getDb();

        // Définition de la requête SQL pour récupérer tous les articles.
        $query = 'SELECT * FROM article ORDER BY date_publication DESC';

        // Exécution de la requête à l'aide d'une méthode utilitaire (executeQuery)
        // qui retourne un tableau associatif en cas de succès.
        $result = self::executeQuery($query);

        // Retourne le résultat s'il s'agit d'un tableau, sinon retourne null.
        return is_array($result) ? $result : null;
    }

    /**
     * Récupère un article par son ID.
     *
     * - Vérifie que l'ID fourni est un nombre.
     * - Prépare et exécute une requête paramétrée pour éviter les injections SQL.
     * - Retourne les informations de l'article sous forme de tableau associatif.
     *
     * @param int $id L'identifiant unique de l'article.
     * @return array|null Retourne un tableau contenant les informations de l'article ou null en cas d'erreur.
     */
    public static function getArticleById($id)
    {
        // Validation de l'ID pour s'assurer qu'il est numérique.
        if (!is_numeric($id)) {
            throw new \InvalidArgumentException('ID d\'article invalide');
        }

        try {
            // Obtention de la connexion PDO via DbConnector.
            $db = DbConnector::dbConnect();

            // Préparation de la requête SQL pour sélectionner l'article par son ID.
            $query = 'SELECT * FROM article WHERE id_article = :id';
            $stmt = $db->prepare($query);

            // Association de l'ID au paramètre :id en précisant qu'il s'agit d'un entier.
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Retourne le premier résultat sous forme de tableau associatif.
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // En cas d'erreur, journalise le message et retourne null.
            error_log('Erreur de base de données dans getArticleById : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupère les 3 derniers articles triés par date de publication décroissante.
     *
     * - Effectue une requête SQL pour récupérer les trois articles les plus récents.
     *
     * @return array|null Retourne un tableau contenant les 3 derniers articles ou null en cas d'erreur.
     */
    public static function getLastThreeArticles()
    {
        try {
            // Connexion à la base de données.
            $db = DbConnector::dbConnect();

            // Préparation de la requête SQL avec limite de 3 articles.
            $query = 'SELECT * FROM article ORDER BY date_publication DESC LIMIT 3';
            $stmt = $db->prepare($query);
            $stmt->execute();

            // Retourne tous les résultats sous forme de tableau associatif.
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Journalise l'erreur et retourne null en cas de problème.
            error_log('Erreur de base de données dans getLastThreeArticles : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupère les commentaires d'un article spécifique.
     *
     * - Vérifie que l'ID de l'article est numérique pour éviter les requêtes malveillantes.
     * - Joint la table des commentaires et celle des utilisateurs pour obtenir des informations
     *   telles que le nom et le prénom de l'auteur du commentaire.
     * - Trie les commentaires par date décroissante.
     *
     * @param int $id_article L'identifiant unique de l'article.
     * @return array|null Retourne un tableau contenant les commentaires ou null en cas d'erreur.
     */
    public static function getCommentsByArticle($id_article)
    {
        // Validation de l'ID de l'article.
        if (!is_numeric($id_article)) {
            throw new \InvalidArgumentException('ID d\'article invalide pour les commentaires');
        }

        try {
            // Connexion à la base de données.
            $db = DbConnector::dbConnect();

            // Requête SQL pour récupérer les commentaires et les informations de l'utilisateur associé.
            $query = 'SELECT c.msg_blog, c.date_commentaire, u.nom, u.prenom 
                      FROM commentaire c
                      INNER JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
                      WHERE c.id_article = :id_article 
                      ORDER BY c.date_commentaire DESC';
            $stmt = $db->prepare($query);

            // Lien du paramètre id_article à la requête.
            $stmt->bindParam(':id_article', $id_article, PDO::PARAM_INT);
            $stmt->execute();

            // Retourne tous les commentaires sous forme de tableau associatif.
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Journalise l'erreur et retourne null en cas de problème.
            error_log('Erreur de base de données dans getCommentsByArticle : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Ajoute un nouvel article dans la base de données.
     *
     * - Vérifie que les données (titre, description, imagePath) ne sont pas vides.
     * - Valide le fichier image en comparant son extension avec une liste d'extensions autorisées.
     * - Enregistre l'article dans la base avec la date de publication définie à l'instant T.
     *
     * @param string $title Le titre de l'article.
     * @param string $description La description de l'article.
     * @param string $imagePath Le chemin de l'image associée à l'article.
     * @return bool Retourne true si l'article a été ajouté avec succès, sinon false.
     * @throws \InvalidArgumentException En cas de données invalides ou d'extension de fichier non autorisée.
     */
    public function addArticle($title, $description, $imagePath)
    {
        // Vérifie que tous les paramètres requis sont fournis.
        if (empty($title) || empty($description) || empty($imagePath)) {
            throw new \InvalidArgumentException('Données de l\'article invalides');
        }

        // Liste des extensions d'image autorisées.
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        // Récupère l'extension du fichier à partir du chemin.
        $fileExtension = pathinfo($imagePath, PATHINFO_EXTENSION);

        // Vérifie que l'extension du fichier est dans la liste des extensions autorisées.
        if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
            throw new \InvalidArgumentException('Type de fichier image invalide');
        }

        try {
            // Connexion à la base via DbConnector.
            $pdo = DbConnector::dbConnect();

            // Préparation de la requête d'insertion pour ajouter l'article.
            $stmt = $pdo->prepare("
                INSERT INTO article (titre, description, image, date_publication) 
                VALUES (:titre, :description, :image, NOW())
            ");

            // Exécution de la requête avec les paramètres fournis.
            return $stmt->execute([
                'titre' => $title,
                'description' => $description,
                'image' => $imagePath
            ]);
        } catch (PDOException $e) {
            // Enregistre l'erreur et retourne false en cas d'échec.
            error_log('Erreur de base de données dans addArticle : ' . $e->getMessage());
            return false;
        }
    }
}
?>