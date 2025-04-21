<?php

namespace Figurine\Controller;

use Figurine\Lib\View;
use Figurine\Lib\FlashMessage;
use Figurine\Model\Review;
use Figurine\Model\Category;
use Figurine\Model\Product;

class ProductController
{
    // Propriété pour stocker l'instance du modèle Produit
    private $productModel;

    /**
     * Constructeur de la classe ProductController.
     * Initialise le modèle Produit pour les opérations liées aux produits.
     */
    public function __construct()
    {
        // Création d'une instance du modèle Produit
        $this->productModel = new Product();
    }

    /**
     * Affiche les 4 derniers produits sur la page d'accueil.
     * Récupère les produits depuis le modèle et les passe à la vue.
     */
    public function showLatestProducts()
    {
        // Utilise la méthode statique pour récupérer les 4 derniers produits
        $products = Product::getLastFourProducts();

        // Appelle la vue d'accueil avec les derniers produits
        View::render('home', ['products' => $products]);
    }

    /**
     * Affiche la liste de tous les produits disponibles.
     * Récupère les produits depuis le modèle et les passe à la vue.
     */
    public function showProducts()
{
    // Récupère toutes les catégories
    $categoryModel = new Category();
    $categories = $categoryModel::getAllCategories();

    // Vérifie si un filtre de catégorie est présent dans l'URL
    if (isset($_GET['categorie']) && is_numeric($_GET['categorie'])) {
        $id_category = intval($_GET['categorie']);
        $products = Product::getProductsByCategory($id_category);
    } else {
        // Sinon, récupère tous les produits
        $products = $this->productModel->getAllProducts();
    }

    // Passe les produits et les catégories à la vue
    View::render('liste-produits', [
        'products' => $products,
        'categories' => $categories
    ]);
}

    /**
     * Affiche les détails d'un produit spécifique.
     * Valide l'ID du produit, récupère ses détails depuis le modèle et les passe à la vue.
     * Redirige vers la liste des produits si le produit n'existe pas.
     * 
     * @param int $id_product L'identifiant unique du produit.
     */
    public function showProductDetails($id_product)
{
    // Valide l'ID du produit
    $id_product = filter_var($id_product, FILTER_VALIDATE_INT);
    if (!$id_product) {
        FlashMessage::addMessage('error', 'ID produit invalide.');
        header('Location: ' . BASE_URL . 'produits');
        exit();
    }

    // Récupère le produit et ses avis
    $product = $this->productModel->getProductById($id_product);
    if (!$product) {
        FlashMessage::addMessage('error', 'Le produit n\'existe pas.');
        header('Location: ' . BASE_URL . 'produits');
        exit();
    }
    
    // Récupère les avis
    $reviewModel = new Review();
    $reviews = $reviewModel->getReviewsByProduct($id_product);

    // Appelle la vue pour afficher les détails du produit et les avis
    View::render('product-detail', [
        'product' => $product,
        'reviews' => $reviews
    ]);
}

    /**
     * Ajoute un nouveau produit.
     *  - Valide les données du formulaire.
     *  - Télécharge l'image dans le dossier dédié en générant un nom unique.
     *  - Enregistre le produit dans la base et redirige vers le tableau de bord admin.
     */
    public function addProduct()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nom'], $_POST['description'], $_POST['prix'], $_POST['taille'], $_FILES['image'])) {
        $nom = htmlspecialchars($_POST['nom']);
        $description = htmlspecialchars($_POST['description']);
        $prix = htmlspecialchars($_POST['prix']);
        $taille = htmlspecialchars($_POST['taille']);
        $image = $_FILES['image'];

        // Traitement de l'image, par exemple téléversement, génération d'un nom unique etc.
        $uploadDir = __DIR__ . '/../../public/uploads/';
        $uniqueFileName = uniqid() . '_' . basename($image['name']);
        $uploadFile = $uploadDir . $uniqueFileName;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($image['tmp_name'], $uploadFile)) {
            $imagePath = $uniqueFileName;
        } else {
            FlashMessage::addMessage('error', 'Erreur lors du téléchargement de l\'image.');
            header('Location: ' . BASE_URL . 'admin');
            exit();
        }

        // Récupérer les catégories sélectionnées (checkboxes ou select multiple)
        $categories = isset($_POST['categories']) ? $_POST['categories'] : [];

        $productModel = new Product();
        $id_product = $productModel->addProduct($nom, $description, $prix, $taille, $imagePath);
        if ($id_product) {
            // Enregistre les associations si des catégories ont été sélectionnées
            if (!empty($categories)) {
                $productModel->addCategoriesToProduct($id_product, $categories);
            }
            FlashMessage::addMessage('success', 'Produit ajouté avec succès.');
        } else {
            FlashMessage::addMessage('error', 'Erreur lors de l\'ajout du produit.');
        }
        header('Location: ' . BASE_URL . 'admin');
        exit();
    }
}
}