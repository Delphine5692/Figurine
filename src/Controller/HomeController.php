<?php

namespace Figurine\Controller;

use Figurine\Model\Product;
use Figurine\Model\Article;
use Figurine\Model\Review;
use Figurine\Lib\View;
use Figurine\Lib\FlashMessage;

class HomeController
{

    public function showHome()
    {
        $productModel = new Product();
        $articleModel = new Article();

        // Récupération des derniers produits et articles
        $products = $productModel->getLastFourProducts();
        $articles = $articleModel->getLastThreeArticles();

        // Produit mis en avant et son dernier avis
        $featuredProduct = Product::getFeaturedProduct();
        $featuredReview = null;
        if ($featuredProduct && !empty($featuredProduct['id_produit'])) {
            $reviewModel = new Review();
            $featuredReview = $reviewModel->getLastReviewByProduct($featuredProduct['id_produit']);
        }

        if (empty($products) && empty($articles)) {
            FlashMessage::addMessage('error', 'Aucun produit ni article n\'a été trouvé.');
            View::render('404');
            exit();
        }

        // On vérifie si on est sur la page d'accueil pour appliquer le style css
        $isHome = true;
        
        // Rendu de la vue d'accueil avec les données
        View::render('home', [
            'products'        => $products,
            'articles'        => $articles,
            'featuredProduct' => $featuredProduct,
            'featuredReview'  => $featuredReview,
            'isHome'          => $isHome
        ]);
    }
}
