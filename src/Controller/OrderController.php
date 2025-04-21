<?php

namespace Figurine\Controller;

use Figurine\Model\Order;
use Figurine\Lib\View;
use Figurine\Lib\FlashMessage;

class OrderController
{
    /**
     * Valide le panier d'un utilisateur.
     * Vérifie si l'utilisateur est connecté, récupère les données du panier et enregistre la commande dans la base de données.
     * Retourne une réponse JSON indiquant le succès ou l'échec de l'opération.
     */
    public function validateCart()
    {
        // Vérifie si l'utilisateur est connecté
        if (!isset($_SESSION['id_utilisateur'])) {
            // Utilisation de FlashMessage en plus pour garder une trace côté client (si besoin)
            FlashMessage::addMessage('error', 'Vous devez être connecté pour valider votre panier.');
            echo json_encode([
                'success' => false, 
                'message' => 'Vous devez être connecté pour valider votre panier.'
            ]);
            return;
        }

        // Récupère l'identifiant de l'utilisateur connecté (stocké dans la session)
        $id_user = $_SESSION['id_utilisateur'];

        // Récupère les données JSON envoyées par la requête (le contenu du panier)
        $data = json_decode(file_get_contents('php://input'), true);

        // Log des données reçues (utile pour le débogage)
        error_log('Données reçues : ' . print_r($data, true));

        // Vérifie si le panier est vide
        if (empty($data)) {
            echo json_encode(['success' => false, 'message' => 'Panier vide.']);
            return;
        }

        // Instantie le modèle de commande
        $orderModel = new Order();

        // Appelle la méthode pour enregistrer la commande dans la base de données
        $idOrder = $orderModel->saveOrder($id_user, $data);

        // Réponse JSON pour indiquer si l'opération a réussi ou échoué
        if ($idOrder) {
            echo json_encode(['success' => true, 'id_commande' => $idOrder]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement de la commande.']);
        }
    }

    /**
     * Affiche les commandes d'un utilisateur.
     * Vérifie si l'utilisateur est connecté, récupère ses commandes et les affiche dans la vue profil.
     */
    public function showUserOrders()
    {
        // Vérifie si l'utilisateur est connecté
        if (!isset($_SESSION['id_utilisateur'])) {
            FlashMessage::addMessage('error', 'Vous devez être connecté pour consulter vos commandes.');
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        // Récupère l'ID de l'utilisateur connecté
        $id_user = $_SESSION['id_utilisateur'];

        // Instantie le modèle de commande
        $orderModel = new Order();

        // Récupère les commandes de l'utilisateur
        $orders = $orderModel->getOrdersByUser($id_user);

        // Passe les commandes à la vue 'profil'
        View::render('profil', ['orders' => $orders]);
    }

    /**
     * Affiche la vue du panier.
     * Charge la vue contenant les informations du panier.
     */
    public function showCart()
    {
        View::render('cart');
    }
}