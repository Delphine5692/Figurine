<?php

namespace Figurine\Controller;

use Figurine\Model\Review;
use Figurine\Lib\FlashMessage;

class ReviewController
{
    /**
     * Traite l'ajout d'un avis sur un produit.
     *
     * - Vérifie que la requête est bien de type POST et que les données nécessaires (id_produit et contenu) sont fournies.
     * - Valide l'ID du produit et nettoie le contenu de l'avis.
     * - En cas d'informations manquantes ou invalides, ajoute un message flash et redirige l'utilisateur vers le détail du produit.
     * - Tente d'enregistrer l'avis via le modèle Review.
     * - Ajoute un message flash de succès ou d'erreur en fonction du résultat.
     * - Redirige l'utilisateur vers la page de détail du produit après traitement.
     */
    public function addReview()
    {
        // Vérifie que la requête est POST et que les paramètres 'id_produit' et 'contenu' existent.
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_produit'], $_POST['contenu'])) {
            
            // Valide et filtre l'identifiant du produit.
            $id_produit = filter_input(INPUT_POST, 'id_produit', FILTER_VALIDATE_INT);
            // Récupère et nettoie le contenu de l'avis.
            $contenu = trim($_POST['contenu']);

            // Si l'ID produit est invalide ou si le contenu est vide, indique une erreur.
            if (!$id_produit || empty($contenu)) {
                FlashMessage::addMessage('error', 'Données manquantes pour l\'avis.');
                header('Location: ' . BASE_URL . 'product/' . $id_produit);
                exit();
            }

            // Crée une instance du modèle Review pour traiter l'ajout de l'avis.
            $avisModel = new Review();
            // Tente d'ajouter l'avis en passant l'ID du produit, l'ID de l'utilisateur (depuis la session) et le contenu.
            if ($avisModel->addReview($id_produit, $_SESSION['id_utilisateur'], $contenu)) {
                FlashMessage::addMessage('success', 'Avis ajouté avec succès.');
            } else {
                FlashMessage::addMessage('error', 'Erreur lors de l\'ajout de l\'avis.');
            }

            // Redirige l'utilisateur vers la page de détail du produit.
            header('Location: ' . BASE_URL . 'product/' . $id_produit);
            exit();
        } else {
            // Si la méthode HTTP n'est pas POST ou si les données nécessaires ne sont pas fournies,
            // affiche un message d'erreur et redirige vers la liste des produits.
            FlashMessage::addMessage('error', 'Requête invalide.');
            header('Location: ' . BASE_URL . 'products');
            exit();
        }
    }

    /**
     * Traite la suppression d'un avis.
     *
     * - Vérifie que la requête est de type POST et que l'ID de l'avis est fourni.
     * - Valide l'ID de l'avis et vérifie que l'utilisateur est connecté.
     * - Tente de supprimer l'avis via le modèle Review.
     * - Ajoute un message flash de succès ou d'erreur en fonction du résultat.
     * - Redirige l'utilisateur vers la page de profil après la suppression.
     */
    public function deleteReview()
    {
        // Vérifie que la requête est POST et que l'ID de l'avis est présent.
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_avis'])) {
            // Valide l'ID de l'avis en utilisant FILTER_VALIDATE_INT.
            $id_avis = filter_input(INPUT_POST, 'id_avis', FILTER_VALIDATE_INT);
            if (!$id_avis) {
                FlashMessage::addMessage('error', 'Avis invalide.');
                header('Location: ' . BASE_URL . 'profil');
                exit();
            }
            
            // Vérifie que l'utilisateur est connecté.
            if (!isset($_SESSION['id_utilisateur'])) {
                FlashMessage::addMessage('error', 'Vous devez être connecté pour supprimer un avis.');
                header('Location: ' . BASE_URL . 'connexion');
                exit();
            }
            
            // Instancie le modèle Review pour effectuer la suppression.
            $reviewModel = new Review();
            if ($reviewModel->deleteReview($id_avis)) {
                FlashMessage::addMessage('success', 'Avis supprimé avec succès.');
            } else {
                FlashMessage::addMessage('error', 'Erreur lors de la suppression de l\'avis.');
            }
            // Redirige l'utilisateur vers sa page de profil.
            header('Location: ' . BASE_URL . 'profil');
            exit();
        } else {
            // En cas de requête invalide, ajoute un message d'erreur et redirige vers la page de profil.
            FlashMessage::addMessage('error', 'Requête invalide.');
            header('Location: ' . BASE_URL . 'profil');
            exit();
        }
    }
}
?>