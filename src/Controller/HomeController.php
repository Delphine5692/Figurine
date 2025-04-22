<?php

namespace Figurine\Controller;

use Figurine\Model\Product;
use Figurine\Model\Article;
use Figurine\Model\Review;
use Figurine\Lib\View;
use Figurine\Lib\FlashMessage;

class HomeController
{
    /**
     * Affiche la page d'accueil.
     *
     * - Récupère les derniers produits et articles depuis leur modèle respectif.
     * - Récupère le produit mis en avant ainsi que son dernier avis (si disponible).
     * - Si aucun produit ou article n'est trouvé, affiche une page 404 avec un message d'erreur.
     * - Définit un flag ($isHome) pour appliquer le style CSS spécifique à la page d'accueil.
     * - Rendu de la vue 'home' avec toutes les données nécessaires passées en paramètres.
     */
    public function showHome()
    {
        // Instancie les modèles pour manipuler les produits et articles.
        $productModel = new Product();
        $articleModel = new Article();

        // Récupération des derniers produits et articles.
        $products = $productModel->getLastFourProducts();
        $articles = $articleModel->getLastThreeArticles();

        // Récupération du produit mis en avant et de son dernier avis.
        $featuredProduct = Product::getFeaturedProduct();
        $featuredReview = null;
        if ($featuredProduct && !empty($featuredProduct['id_produit'])) {
            $reviewModel = new Review();
            $featuredReview = $reviewModel->getLastReviewByProduct($featuredProduct['id_produit']);
        }

        // Si aucun produit ni article n'est trouvé, affiche un message d'erreur et redirige vers une page 404.
        if (empty($products) && empty($articles)) {
            FlashMessage::addMessage('error', 'Aucun produit ni article n\'a été trouvé.');
            View::render('404');
            exit();
        }

        // Indique que l'on se trouve sur la page d'accueil pour appliquer le style CSS spécifique.
        $isHome = true;

        // Rendu de la vue d'accueil avec les données préparées.
        View::render('home', [
            'products'        => $products,
            'articles'        => $articles,
            'featuredProduct' => $featuredProduct,
            'featuredReview'  => $featuredReview,
            'isHome'          => $isHome
        ]);
    }
}
?>