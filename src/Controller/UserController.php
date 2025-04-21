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
     * Vérifie les identifiants fournis et initialise la session en cas de succès.
     * Redirige vers la page appropriée en fonction du rôle de l'utilisateur.
     */
    public function login()
    {
        if (!empty($_POST['mail']) && !empty($_POST['mdp'])) {
            $mail = htmlspecialchars($_POST['mail']);
            $mdp = $_POST['mdp'];

            $userModel = new User();
            $user = $userModel->checkLogin($mail, $mdp);

            // Vérifier si l'utilisateur existe et si son compte a été supprimé
            if ($user) {
                if (isset($user['statut']) && $user['statut'] === 'supprimer') {
                    FlashMessage::addMessage('error', 'Votre compte a été supprimé. Contactez le service client si vous souhaitez le réactiver.');
                    View::render('login');
                    exit;
                }

                // Connexion réussie
                $_SESSION['id_utilisateur'] = $user['id_utilisateur'];
                $_SESSION['nom'] = $user['nom'];
                $_SESSION['prenom'] = $user['prenom'];
                $_SESSION['role'] = $user['role'];

                header('Location: home');
                exit;
            } else {
                FlashMessage::addMessage('error', 'Identifiants incorrects.');
                View::render('login');
                exit;
            }
        } else {
            View::render('login');
            exit;
        }
    }

    /**
     * Gère la création d'un compte utilisateur.
     * Valide les données du formulaire, hache le mot de passe et enregistre l'utilisateur.
     */
    public function createAccount()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $mail = htmlspecialchars($_POST['mail']);
            $mdp = $_POST['mdp'];
            $mdp_confirm = $_POST['mdp_confirm'];

            if ($mdp !== $mdp_confirm) {
                // Affiche le formulaire en transmettant le message d'erreur
                FlashMessage::addMessage('error', 'Les mots de passe ne correspondent pas.');
                View::render('create-account');
                return;
            }

            $mdp_hache = password_hash($mdp, PASSWORD_DEFAULT);

            $userModel = new User();
            if ($userModel->createUser($nom, $prenom, $mail, $mdp_hache)) {
                // Inscription réussie : on peut rediriger vers la page de connexion
                header('Location: login');
                exit;
            } else {
                // L'email existe déjà, affiche à nouveau le formulaire avec un message d'erreur
                FlashMessage::addMessage('error', 'Cet email est déjà utilisé.');
                View::render('create-account');
                return;
            }
        } else {
            View::render('create-account');
            exit;
        }
    }

    /**
     * Déconnecte l'utilisateur en détruisant la session.
     * Redirige vers la page d'accueil ou la page de connexion.
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
     * Récupère les informations utilisateur, les commandes et les commentaires associés.
     */
    public function showProfile()
    {
        if (!isset($_SESSION['id_utilisateur'])) {
            View::render('connexion');
            exit;
        }

        $id_user = $_SESSION['id_utilisateur'];

        $userModel = new User();
        $user = $userModel->getUserById($id_user);

        $commentModel = new Comment();
        $comments = $commentModel->getCommentsByUser($id_user);

        $orderModel = new Order();
        $orders = $orderModel->getOrdersByUser($id_user);

        // Récupération des avis de l'utilisateur
        $reviewModel = new Review();
        $reviews = $reviewModel->getReviewsByUser($id_user);

        View::render('profil', [
            'user'     => $user,
            'comments' => $comments,
            'orders'   => $orders,
            'reviews'  => $reviews
        ]);
    }

    /**
     * Supprime le compte de l'utilisateur connecté.
     * Supprime les données utilisateur et détruit la session.
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

        if (!empty($orders)) {
            if ($userModel->updateStatus($id_user, 'supprimer')) {
                session_destroy();
                FlashMessage::addMessage('success', 'Compte mis en attente de suppression.');
                header('Location: home');
                exit;
            } else {
                FlashMessage::addMessage('error', 'Erreur lors de la mise à jour du statut de votre compte.');
                header('Location: profil');
                exit;
            }
        } else {
            if ($userModel->deleteUser($id_user)) {
                session_destroy();
                FlashMessage::addMessage('success', 'Compte supprimé avec succès.');
                header('Location: home');
                exit;
            } else {
                FlashMessage::addMessage('error', 'Erreur lors de la suppression de votre compte.');
                header('Location: profil');
                exit;
            }
        }
    }

    /**
     * Met à jour l'adresse de l'utilisateur connecté.
     * Valide les données du formulaire et met à jour l'adresse dans la base de données.
     */
    public function updateAddress()
    {
        if (!isset($_SESSION['id_utilisateur'])) {
            View::render('login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adresse'])) {
            $adress = htmlspecialchars($_POST['adresse']);
            $id_user = $_SESSION['id_utilisateur'];

            $userModel = new User();
            $userModel->updateAddress($id_user, $adress);

            FlashMessage::addMessage('success', 'Adresse modifiée avec succès.');
            header('Location: profil');
            exit;
        }

        // En cas de requête invalide, on redirige avec un message d'erreur
        FlashMessage::addMessage('error', 'Requête invalide.');
        header('Location: profil');
        exit;
    }
}
