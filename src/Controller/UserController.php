<?php

namespace Figurine\Controller;

use Figurine\Model\Comment;
use Figurine\Model\User;
use Figurine\Model\Order;
use Figurine\Model\Review;
use Figurine\Lib\View;
use Figurine\Lib\FlashMessage;

class UserController
{
    /**
     * Gère la connexion d'un utilisateur.
     *
     * - Vérifie que les champs 'mail' et 'mdp' sont fournis dans le formulaire.
     * - Nettoie l'email pour éviter les risques XSS.
     * - Vérifie les identifiants en appelant la méthode checkLogin du modèle User.
     * - Si le compte est marqué comme supprimé, affiche un message d'erreur et réaffiche le formulaire de connexion.
     * - En cas de réussite, initialise la session avec les informations de l'utilisateur et redirige vers la page d'accueil.
     * - Sinon, affiche un message d'erreur dans le formulaire de connexion.
     */
    public function login()
    {
        if (!empty($_POST['mail']) && !empty($_POST['mdp'])) {
            // Nettoyage de l'email pour se prémunir contre les injections.
            $mail = htmlspecialchars($_POST['mail']);
            $mdp = $_POST['mdp'];

            // Vérifie les identifiants de connexion.
            $userModel = new User();
            $user = $userModel->checkLogin($mail, $mdp);

            // Si l'utilisateur est trouvé.
            if ($user) {
                // Vérifie si le compte est marqué comme supprimé.
                if (isset($user['statut']) && $user['statut'] === 'supprimer') {
                    FlashMessage::addMessage('error', 'Votre compte a été supprimé. Contactez le service client si vous souhaitez le réactiver.');
                    View::render('login');
                    exit;
                }

                // Connexion réussie : on enregistre dans la session les informations essentielles.
                $_SESSION['id_utilisateur'] = $user['id_utilisateur'];
                $_SESSION['nom'] = $user['nom'];
                $_SESSION['prenom'] = $user['prenom'];
                $_SESSION['role'] = $user['role'];

                // Redirige vers la page d'accueil.
                header('Location: home');
                exit;
            } else {
                // Identifiants incorrects, affiche un message d'erreur et réaffiche le formulaire.
                FlashMessage::addMessage('error', 'Identifiants incorrects.');
                View::render('login');
                exit;
            }
        } else {
            // Si le formulaire est incomplet, affiche simplement le formulaire de connexion.
            View::render('login');
            exit;
        }
    }

    /**
     * Gère la création d'un compte utilisateur.
     *
     * - Vérifie que la requête est une POST.
     * - Récupère et nettoie les données du formulaire (nom, prénom, email).
     * - Vérifie que les mots de passe saisis correspondent avant de les hacher.
     * - Tente d'inscrire l'utilisateur en appelant la méthode createUser du modèle User.
     * - Redirige vers la page de connexion si l'inscription est réussie, sinon réaffiche le formulaire avec un message d'erreur.
     */
    public function createAccount()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération et nettoyage des données du formulaire.
            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $mail = htmlspecialchars($_POST['mail']);
            $mdp = $_POST['mdp'];
            $mdp_confirm = $_POST['mdp_confirm'];

            // Vérifie que les mots de passe correspondent.
            if ($mdp !== $mdp_confirm) {
                FlashMessage::addMessage('error', 'Les mots de passe ne correspondent pas.');
                View::render('create-account');
                return;
            }

            // Hache le mot de passe avant de l'enregistrer.
            $mdp_hache = password_hash($mdp, PASSWORD_DEFAULT);

            $userModel = new User();
            // Tente la création de l'utilisateur dans la base de données.
            if ($userModel->createUser($nom, $prenom, $mail, $mdp_hache)) {
                // Si réussi, redirige vers la page de connexion.
                FlashMessage::addMessage('success', 'Inscription réussie. Vous pouvez vous connecter.');
                View::render('login');
                exit;
            } else {
                // Si l'email est déjà utilisé, affiche le formulaire avec un message d'erreur.
                FlashMessage::addMessage('error', 'Cet email est déjà utilisé.');
                View::render('create-account');
                return;
            }
        } else {
            // Si la requête n'est pas POST, affiche le formulaire de création de compte.
            View::render('create-account');
            exit;
        }
    }

    /**
     * Déconnecte l'utilisateur en détruisant la session.
     * Redirige ensuite vers la page d'accueil.
     */
    public function logout()
    {
        if (isset($_SESSION['id_utilisateur'])) {
            session_destroy();
        }
        header('Location: home');
        exit;
    }

    /**
     * Affiche le profil de l'utilisateur connecté.
     *
     * - Vérifie que l'utilisateur est connecté.
     * - Récupère les informations utilisateur depuis le modèle User.
     * - Récupère également les commandes, commentaires et avis associés à l'utilisateur.
     * - Transmet ces données à la vue 'profil'.
     */
    public function showProfile()
    {
        if (!isset($_SESSION['id_utilisateur'])) {
            View::render('connexion');
            exit;
        }

        $id_user = $_SESSION['id_utilisateur'];

        // Récupère les informations de l'utilisateur.
        $userModel = new User();
        $user = $userModel->getUserById($id_user);

        // Récupère les commentaires postés par l'utilisateur.
        $commentModel = new Comment();
        $comments = $commentModel->getCommentsByUser($id_user);

        // Récupère les commandes passées par l'utilisateur.
        $orderModel = new Order();
        $orders = $orderModel->getOrdersByUser($id_user);

        // Récupère les avis écrits par l'utilisateur.
        $reviewModel = new Review();
        $reviews = $reviewModel->getReviewsByUser($id_user);

        // Affiche la vue "profil" avec toutes les informations récupérées.
        View::render('profil', [
            'user'     => $user,
            'comments' => $comments,
            'orders'   => $orders,
            'reviews'  => $reviews
        ]);
    }

    /**
     * Supprime le compte de l'utilisateur connecté.
     *
     * - Vérifie d'abord que l'utilisateur est connecté.
     * - Récupère l'ID de l'utilisateur et vérifie si des commandes existent pour cet utilisateur.
     * - Si des commandes existent, met le compte en attente de suppression (statut "supprimer").
     * - Sinon, supprime définitivement le compte.
     * - Détruit la session et redirige vers la page d'accueil avec un message flash.
     */
    public function deleteAccount()
    {
        if (!isset($_SESSION['id_utilisateur'])) {
            View::render('connexion');
            exit;
        }

        $id_user = $_SESSION['id_utilisateur'];
        $userModel = new User();
        $orderModel = new Order();
        $orders = $orderModel->getOrdersByUser($id_user);

        // Si l'utilisateur possède des commandes, on met à jour son statut plutôt que de supprimer le compte.
        if (!empty($orders)) {
            if ($userModel->updateStatus($id_user, 'supprimer')) {
                session_destroy();
                FlashMessage::addMessage('success', 'Compte mis en attente de suppression.');
                View::render('home');
                exit;
            } else {
                FlashMessage::addMessage('error', 'Erreur lors de la mise à jour du statut de votre compte.');
                View::render('profil');
                exit;
            }
        } else {
            // Si aucune commande n'existe, on peut supprimer le compte.
            if ($userModel->deleteUser($id_user)) {
                session_destroy();
                FlashMessage::addMessage('success', 'Compte supprimé avec succès.');
                View::render('home');
                exit;
            } else {
                FlashMessage::addMessage('error', 'Erreur lors de la suppression de votre compte.');
                View::render('profil');
                exit;
            }
        }
    }

    /**
     * Met à jour l'adresse de l'utilisateur connecté.
     *
     * - Vérifie que l'utilisateur est connecté et que la méthode est POST.
     * - Récupère l'adresse fournie, la nettoie et met à jour l'utilisateur dans la base de données.
     * - Affiche un message flash de succès et redirige vers le profil.
     * - En cas d'erreur ou de données manquantes, affiche un message d'erreur et redirige vers le profil.
     */
    public function updateAddress()
    {
        if (!isset($_SESSION['id_utilisateur'])) {
            View::render('login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adresse'])) {
            // Nettoie l'adresse pour éviter les attaques XSS.
            $adress = htmlspecialchars($_POST['adresse']);
            $id_user = $_SESSION['id_utilisateur'];

            $userModel = new User();
            $userModel->updateAddress($id_user, $adress);

            FlashMessage::addMessage('success', 'Adresse modifiée avec succès.');
            View::render('profil');
            exit;
        }

        // Si la requête n'est pas valide, affiche un message d'erreur et redirige vers le profil.
        FlashMessage::addMessage('error', 'Requête invalide.');
        View::render('profil');
        exit;
    }
}
?>