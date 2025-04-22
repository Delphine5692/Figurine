<?php

namespace Figurine\Controller;

use Figurine\Model\Article;
use Figurine\Model\Comment;
use Figurine\Lib\View;
use Figurine\Lib\FlashMessage;

class ArticleController
{
    /**
     * Affiche la liste de tous les articles.
     *
     * - Récupère tous les articles via le modèle Article.
     * - Si aucun article n'est trouvé, ajoute un message flash d'erreur
     *   et redirige vers la page d'accueil.
     * - Sinon, transmet les articles à la vue 'liste-article' pour affichage.
     */
    public function showArticles()
    {
        // Récupérer tous les articles depuis la base.
        $articles = Article::getAllArticles();

        // Si le modèle retourne null, signifie qu'aucun article n'a été trouvé.
        if ($articles === null) {
            // Ajoute un message flash d'erreur.
            FlashMessage::addMessage('error', 'Aucun article trouvé.');
            // Affiche la page d'accueil.
            View::render('home');
            exit();
        }

        // Affiche la vue 'liste-article' avec les articles récupérés.
        View::render('liste-article', ['articles' => $articles]);
    }

    /**
     * Affiche un article spécifique et ses commentaires associés.
     *
     * @param int $id L'identifiant de l'article à afficher.
     *
     * - Récupère l'article correspondant à l'ID donné.
     * - Récupère tous les commentaires liés à cet article via le modèle Comment.
     * - Si l'article n'existe pas, affiche un message flash d'erreur et redirige vers la liste des articles.
     * - Sinon, affiche la vue 'article-detail' en lui passant l'article et ses commentaires.
     */
    public function showArticle($id)
    {
        // Récupère l'article en fonction de l'ID.
        $article = Article::getArticleById($id);

        // Récupère les commentaires associés à cet article.
        $comments = (new Comment())->getComments($id);

        // Si l'article n'existe pas, prévient l'utilisateur et redirige.
        if ($article === null) {
            FlashMessage::addMessage('error', "L'article n'existe pas.");
            header('Location: ' . BASE_URL . 'articles');
            exit();
        }

        // Affiche la vue 'article-detail' avec les données de l'article et des commentaires.
        View::render('article-detail', [
            'article' => $article,
            'comments' => $comments
        ]);
    }

    /**
     * Ajoute un nouvel article.
     *
     * - Vérifie que la requête est une requête POST et que les données nécessaires (titre, description, image) sont présentes.
     * - Échappe les données textuelles afin d'éviter les injections de code (HTML / XSS).
     * - Gère le téléchargement de l'image :
     *   • Définit le dossier de destination pour le téléchargement.
     *   • Génère un nom de fichier unique pour éviter les conflits.
     *   • Crée le dossier de destination s'il n'existe pas.
     *   • Tente de déplacer le fichier temporaire vers le dossier de destination.
     * - En cas d'échec du téléchargement, ajoute un message flash d'erreur et redirige vers l'administration.
     * - Si le téléchargement réussit, enregistre l'article en base via le modèle Article.
     * - Ajoute un message flash de succès ou d'erreur en fonction du résultat,
     *   puis redirige vers le tableau de bord admin.
     */
    public function addArticle()
    {
        // Vérifie que la méthode HTTP est POST et que tous les champs requis sont fournis.
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titre'], $_POST['description'], $_FILES['image'])) {

            // Échappe les données textuelles pour protéger contre les injections de code.
            $title = htmlspecialchars($_POST['titre']);
            $description = htmlspecialchars($_POST['description']);
            $image = $_FILES['image'];

            // Définit le dossier où l'image sera stockée.
            $uploadDir = __DIR__ . '/../../public/uploads/';
            // Génère un nom de fichier unique pour éviter les conflits grâce à la fonction uniqid().
            $uniqueFileName = uniqid() . '_' . basename($image['name']);
            $uploadFile = $uploadDir . $uniqueFileName;

            // Si le dossier de destination n'existe pas, le créer avec les permissions adéquates.
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Tente de déplacer le fichier téléchargé depuis le dossier temporaire vers le dossier de destination.
            if (move_uploaded_file($image['tmp_name'], $uploadFile)) {
                // Si tout se passe bien, on utilise le nom de fichier unique comme référence dans la base de données.
                $imagePath = $uniqueFileName;
            } else {
                // En cas d'erreur lors du téléchargement, informe l'utilisateur via un message flash,
                // puis redirige vers la page d'administration.
                FlashMessage::addMessage('error', 'Erreur lors du téléchargement de l\'image.');
                header('Location: ' . BASE_URL . 'admin');
                exit;
            }

            // Création d'une instance du modèle Article.
            $articleModel = new Article();
            // Tente d'enregistrer l'article dans la base de données.
            if ($articleModel->addArticle($title, $description, $imagePath)) {
                // Message de succès si l'ajout a fonctionné.
                FlashMessage::addMessage('success', 'Article ajouté avec succès.');
            } else {
                // Message d'erreur sinon.
                FlashMessage::addMessage('error', 'Une erreur est survenue lors de l\'ajout de l\'article.');
            }

            // Redirige vers le tableau de bord admin après le traitement.
            header('Location: ' . BASE_URL . 'admin');
            exit;
        }

        // Si la requête n'est pas conforme (données manquantes), affiche un message d'erreur et redirige.
        FlashMessage::addMessage('error', 'Données manquantes.');
        header('Location: ' . BASE_URL . 'admin');
        exit;
    }
}