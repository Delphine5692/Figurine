<?php

namespace Figurine\Controller;

use Figurine\Model\Review;
use Figurine\Lib\FlashMessage;
use Figurine\Lib\View;

class ReviewController
{
    /**
     * Traite l'ajout d'un avis sur un produit.
     */
    public function addReview()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
            isset($_POST['id_produit'], $_POST['contenu'])) {
            
            $id_produit = filter_input(INPUT_POST, 'id_produit', FILTER_VALIDATE_INT);
            $contenu = trim($_POST['contenu']);

            if (!$id_produit || empty($contenu)) {
                FlashMessage::addMessage('error', 'Données manquantes pour l\'avis.');
                header('Location: ' . BASE_URL . 'product/' . $id_produit);
                exit();
            }

            $avisModel = new Review();
            if ($avisModel->addReview($id_produit, $_SESSION['id_utilisateur'], $contenu)) {
                FlashMessage::addMessage('success', 'Avis ajouté avec succès.');
            } else {
                FlashMessage::addMessage('error', 'Erreur lors de l\'ajout de l\'avis.');
            }

            header('Location: ' . BASE_URL . 'product/' . $id_produit);
            exit();
        } else {
            FlashMessage::addMessage('error', 'Requête invalide.');
            header('Location: ' . BASE_URL . 'products');
            exit();
        }
    }


    public function deleteReview()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_avis'])) {
            $id_avis = filter_input(INPUT_POST, 'id_avis', FILTER_VALIDATE_INT);
            if (!$id_avis) {
                FlashMessage::addMessage('error', 'Avis invalide.');
                header('Location: ' . BASE_URL . 'profil');
                exit();
            }
            
            // Vérifiez si l'utilisateur est connecté
            if (!isset($_SESSION['id_utilisateur'])) {
                FlashMessage::addMessage('error', 'Vous devez être connecté pour supprimer un avis.');
                header('Location: ' . BASE_URL . 'connexion');
                exit();
            }
            
            $reviewModel = new Review();
            if ($reviewModel->deleteReview($id_avis)) {
                FlashMessage::addMessage('success', 'Avis supprimé avec succès.');
            } else {
                FlashMessage::addMessage('error', 'Erreur lors de la suppression de l\'avis.');
            }
            header('Location: ' . BASE_URL . 'profil');
            exit();
        } else {
            FlashMessage::addMessage('error', 'Requête invalide.');
            header('Location: ' . BASE_URL . 'profil');
            exit();
        }
    }
}