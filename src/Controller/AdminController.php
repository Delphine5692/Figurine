<?php

namespace Figurine\Controller;

use Figurine\Model\User;
use Figurine\Model\Comment;
use Figurine\Model\Article;
use Figurine\Model\Admin;
use Figurine\Model\Order;
use Figurine\Lib\View;
use Figurine\Lib\FlashMessage;
use Figurine\Model\Category;
use Figurine\Model\ContactMessage;


class AdminController
{
    /**
     * Constructeur de la classe AdminController.
     * Vérifie que l'utilisateur est connecté et possède le rôle d'administrateur.
     * Si ce n'est pas le cas, il est redirigé vers la page de connexion.
     */
    public function __construct()
    {
        // Vérifier la session et le rôle admin
        if (!isset($_SESSION['id_utilisateur']) || $_SESSION['role'] !== 'admin') {
            header('Location: login');
            exit;
        }
    }

    /**
     * Affiche le tableau de bord de l'administrateur.
     * Récupère et transmet à la vue la liste des utilisateurs, commentaires et articles.
     */
    public function showDashboard()
    {
        $userModel = new User();
        $commentModel = new Comment();
        $articleModel = new Article();

        try {
            $users = $userModel->getAllUsers();
            $comments = $commentModel->getAllComments();
            $articles = $articleModel->getAllArticles();
            $categories = Category::getAllCategories();
        } catch (\Exception $e) {
            FlashMessage::addMessage('error', 'Erreur lors du chargement des données.');
            header('Location: ' . BASE_URL . 'admin');
            exit;
        }

        View::render('admin', [
            'users' => $users,
            'comments' => $comments,
            'articles' => $articles,
            'categories' => $categories
        ]);
    }

    /**
     * Supprime un utilisateur depuis le tableau de bord admin.
     * - Empêche la suppression du compte de l'administrateur actuellement connecté.
     * - Vérifie si l'utilisateur a passé une commande.
     *   - S'il a passé une commande, le compte est marqué comme "supprimer".
     *   - Sinon, l'utilisateur est supprimé physiquement de la base de données.
     * Affiche ensuite un message de succès ou d'erreur à l'aide d'un message flash.
     */
    public function deleteUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_utilisateur'])) {
            $id_user = intval($_POST['id_utilisateur']);

            if ($id_user === $_SESSION['id_utilisateur']) {
                FlashMessage::addMessage('error', 'Vous ne pouvez pas supprimer votre propre compte.');
                header('Location: ' . BASE_URL . 'admin');
                exit;
            }

            $orderModel = new Order();
            if ($orderModel->hasOrders($id_user)) {
                $userModel = new User();
                if ($userModel->updateStatus($id_user, 'supprimer')) {
                    FlashMessage::addMessage('success', 'L\'utilisateur a été marqué comme supprimé.');
                } else {
                    FlashMessage::addMessage('error', 'Erreur lors de la mise à jour du statut de l\'utilisateur.');
                }
            } else {
                $adminModel = new Admin();
                if ($adminModel->deleteUser($id_user)) {
                    FlashMessage::addMessage('success', 'Utilisateur supprimé avec succès.');
                } else {
                    FlashMessage::addMessage('error', 'Erreur lors de la suppression de l\'utilisateur.');
                }
            }
            header('Location: ' . BASE_URL . 'admin');
            exit;
        }

        FlashMessage::addMessage('error', 'Requête invalide.');
        header('Location: ' . BASE_URL . 'admin');
        exit;
    }

    public function contactMessages() {
        $contactModel = new ContactMessage();
        $messages = $contactModel->getMessages();
        View::render('admin/contact_messages', ['messages' => $messages]);
    }
}
