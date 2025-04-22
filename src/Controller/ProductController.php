<?php

namespace Figurine\Controller;

use Figurine\Lib\View;
use Figurine\Lib\FlashMessage;
use Figurine\Model\Review;
use Figurine\Model\Category;
use Figurine\Model\Product;

class ProductController
{
    // Propriété pour stocker l'instance du modèle Produit.
    private $productModel;

    /**
     * Constructeur de la classe ProductController.
     * Initialise le modèle Produit pour réaliser les opérations ultérieures liées aux produits.
     */
    public function __construct()
    {
        // Création d'une instance du modèle Produit.
        $this->productModel = new Product();
    }

    /**
     * Affiche les 4 derniers produits sur la page d'accueil.
     *
     * - Récupère les 4 derniers produits en utilisant une méthode statique du modèle Product.
     * - Transmet ces produits à la vue 'home' pour affichage.
     */
    public function showLatestProducts()
    {
        // Récupère les 4 derniers produits passant par la méthode statique.
        $products = Product::getLastFourProducts();

        // Appelle la vue 'home' en lui passant les produits récupérés.
        View::render('home', ['products' => $products]);
    }

    /**
     * Affiche la liste de tous les produits disponibles.
     *
     * - Récupère la liste de toutes les catégories pour permettre un filtrage.
     * - Si un filtre de catégorie est présent dans l'URL, récupère uniquement les produits de cette catégorie.
     * - Sinon, récupère tous les produits via le modèle.
     * - Transmet les produits ainsi que les catégories à la vue 'liste-produits'.
     */
    public function showProducts()
    {
        // Instancie le modèle Category pour récupérer toutes les catégories.
        $categoryModel = new Category();
        $categories = $categoryModel::getAllCategories();

        // Vérifie si un filtre de catégorie est présent dans l'URL et est un nombre.
        if (isset($_GET['categorie']) && is_numeric($_GET['categorie'])) {
            $id_category = intval($_GET['categorie']);
            // Récupère les produits correspondant à la catégorie filtrée.
            $products = Product::getProductsByCategory($id_category);
        } else {
            // Sinon, récupère tous les produits.
            $products = $this->productModel->getAllProducts();
        }

        // Appelle la vue 'liste-produits' en passant les données nécessaires :
        // les produits récupérés et la liste de toutes les catégories.
        View::render('liste-produits', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    /**
     * Affiche les détails d'un produit spécifique.
     *
     * - Valide l'ID du produit passé en paramètre.
     * - Récupère les détails du produit depuis le modèle.
     * - Si le produit n'existe pas, affiche un message d'erreur et redirige vers la liste des produits.
     * - Récupère les avis associés au produit.
     * - Transmet les détails du produit et ses avis à la vue 'product-detail'.
     *
     * @param int $id_product L'identifiant unique du produit à afficher.
     */
    public function showProductDetails($id_product)
    {
        // Valide l'ID du produit en utilisant un filtre.
        $id_product = filter_var($id_product, FILTER_VALIDATE_INT);
        if (!$id_product) {
            FlashMessage::addMessage('error', 'ID produit invalide.');
            header('Location: ' . BASE_URL . 'produits');
            exit();
        }

        // Récupère les détails du produit.
        $product = $this->productModel->getProductById($id_product);
        if (!$product) {
            FlashMessage::addMessage('error', 'Le produit n\'existe pas.');
            header('Location: ' . BASE_URL . 'produits');
            exit();
        }

        // Récupère les avis du produit via une instance du modèle Review.
        $reviewModel = new Review();
        $reviews = $reviewModel->getReviewsByProduct($id_product);

        // Transmet les données (produit et avis) à la vue 'product-detail'.
        View::render('product-detail', [
            'product' => $product,
            'reviews' => $reviews
        ]);
    }

    /**
     * Ajoute un nouveau produit.
     *
     * - Vérifie que la méthode HTTP est POST et que tous les champs requis du formulaire sont fournis (nom, description, prix, taille et image).
     * - Échappe et nettoie les données textuelles pour éviter les injections de code (HTML/XSS).
     * - Gère le téléchargement de l'image :
     *   • Définit le dossier de destination pour le téléchargement.
     *   • Génère un nom de fichier unique pour éviter les conflits.
     *   • Crée le dossier de destination s'il n'existe pas.
     *   • Tente de déplacer le fichier temporaire vers ce dossier.
     * - En cas d'échec du téléchargement, ajoute un message flash d'erreur et redirige vers le tableau de bord admin.
     * - Si le téléchargement réussit, enregistre le produit dans la base via le modèle.
     * - Si des catégories ont été sélectionnées, enregistre les associations entre le produit et les catégories.
     * - Affiche un message flash de succès ou d'erreur en fonction du résultat, puis redirige vers le tableau de bord admin.
     */
    public function addProduct()
    {
        // Vérifie que la requête est POST et que tous les champs requis existent.
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nom'], $_POST['description'], $_POST['prix'], $_POST['taille'], $_FILES['image'])) {
            // Nettoie et échappe les données du formulaire.
            $nom = htmlspecialchars($_POST['nom']);
            $description = htmlspecialchars($_POST['description']);
            $prix = htmlspecialchars($_POST['prix']);
            $taille = htmlspecialchars($_POST['taille']);
            $image = $_FILES['image'];

            // Traitement du téléchargement de l'image.
            // Définit le dossier de destination pour les images uploadées.
            $uploadDir = __DIR__ . '/../../public/uploads/';
            // Génère un nom de fichier unique pour éviter les conflits.
            $uniqueFileName = uniqid() . '_' . basename($image['name']);
            $uploadFile = $uploadDir . $uniqueFileName;
            // Si le répertoire de destination n'existe pas, le crée.
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Tente de déplacer le fichier uploadé vers le dossier de destination.
            if (move_uploaded_file($image['tmp_name'], $uploadFile)) {
                $imagePath = $uniqueFileName;
            } else {
                // En cas d'échec du téléchargement de l'image,
                // ajoute un message flash d'erreur et redirige vers le tableau de bord admin.
                FlashMessage::addMessage('error', 'Erreur lors du téléchargement de l\'image.');
                header('Location: ' . BASE_URL . 'admin');
                exit();
            }

            // Récupère l'ensemble des catégories sélectionnées (par exemple issues d'un champ de type checkbox ou select multiple).
            $categories = isset($_POST['categories']) ? $_POST['categories'] : [];

            // Ajoute le produit en utilisant le modèle Product.
            $productModel = new Product();
            $id_product = $productModel->addProduct($nom, $description, $prix, $taille, $imagePath);
            if ($id_product) {
                // Si des catégories ont été sélectionnées, enregistre l'association entre le produit et ces catégories.
                if (!empty($categories)) {
                    $productModel->addCategoriesToProduct($id_product, $categories);
                }
                // Message flash de succès.
                FlashMessage::addMessage('success', 'Produit ajouté avec succès.');
            } else {
                // Message flash d'erreur en cas d'échec de l'ajout en base de données.
                FlashMessage::addMessage('error', 'Erreur lors de l\'ajout du produit.');
            }
            // Redirection vers le tableau de bord admin.
            header('Location: ' . BASE_URL . 'admin');
            exit();
        }
    }
}
?>