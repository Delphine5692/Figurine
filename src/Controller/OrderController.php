<?php

namespace Figurine\Controller;

use Figurine\Model\Order;
use Figurine\Lib\View;
use Figurine\Lib\FlashMessage;

class OrderController
{
    /**
     * Valide le panier d'un utilisateur.
     *
     * - Vérifie que l'utilisateur est connecté.
     * - Récupère les données du panier envoyées au format JSON via php://input.
     * - Enregistre la commande dans la base de données en appelant le modèle Order.
     * - Retourne une réponse JSON indiquant le succès ou l'échec de l'opération.
     */
    public function validateCart()
    {
        // Vérifie si l'utilisateur est connecté.
        if (!isset($_SESSION['id_utilisateur'])) {
            // Ajoute un message flash d'erreur pour informer l'utilisateur.
            FlashMessage::addMessage('error', 'Vous devez être connecté pour valider votre panier.');
            // Retourne une réponse JSON indiquant l'échec, en raison de l'absence de connexion.
            echo json_encode([
                'success' => false, 
                'message' => 'Vous devez être connecté pour valider votre panier.'
            ]);
            return;
        }
        
        // Récupère l'identifiant de l'utilisateur connecté depuis la session.
        $id_user = $_SESSION['id_utilisateur'];
        
        // Récupère le contenu du panier envoyé en POST (données JSON) depuis le flux d'entrée.
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Log pour le débogage : affiche dans les logs les données reçues.
        error_log('Données reçues : ' . print_r($data, true));
        
        // Vérifie que le panier n'est pas vide.
        if (empty($data)) {
            echo json_encode(['success' => false, 'message' => 'Panier vide.']);
            return;
        }
        
        // Instancie le modèle Order pour gérer les commandes.
        $orderModel = new Order();
        
        // Enregistre la commande dans la base, en passant l'ID de l'utilisateur et les données du panier.
        $idOrder = $orderModel->saveOrder($id_user, $data);
        
        // Retourne une réponse JSON indiquant si l'enregistrement de la commande a réussi.
        if ($idOrder) {
            echo json_encode(['success' => true, 'id_commande' => $idOrder]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement de la commande.']);
        }
    }
    
    /**
     * Affiche les commandes d'un utilisateur.
     *
     * - Vérifie si l'utilisateur est connecté.
     * - Récupère l'ID de l'utilisateur connecté et instancie le modèle Order.
     * - Récupère les commandes associées à cet utilisateur.
     * - Affiche la vue 'profil' en y passant les commandes récupérées.
     */
    public function showUserOrders()
    {
        // Vérifie si l'utilisateur est connecté.
        if (!isset($_SESSION['id_utilisateur'])) {
            // Ajoute un message flash d'erreur si l'utilisateur n'est pas identifié.
            FlashMessage::addMessage('error', 'Vous devez être connecté pour consulter vos commandes.');
            // Redirige l'utilisateur vers la page de connexion.
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        
        // Récupère l'ID de l'utilisateur connecté.
        $id_user = $_SESSION['id_utilisateur'];
        
        // Instancie le modèle Order pour récupérer les données de commande.
        $orderModel = new Order();
        
        // Récupère toutes les commandes associées à l'utilisateur.
        $orders = $orderModel->getOrdersByUser($id_user);
        
        // Affiche la vue 'profil' en passant les commandes récupérées.
        View::render('profil', ['orders' => $orders]);
    }
    
    /**
     * Affiche la vue du panier.
     *
     * Simplement responsable de charger la vue contenant les informations du panier.
     */
    public function showCart()
    {
        // Rendu de la vue 'cart' qui affiche les détails du panier.
        View::render('cart');
    }
}
?>