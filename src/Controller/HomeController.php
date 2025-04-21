<?php

namespace Figurine\Controller;

use Figurine\Model\Collections;
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
        $collectionsModel = new Collections();

        // Récupération des derniers produits et articles
        $products = $productModel->getLastFourProducts();
        $articles = $articleModel->getLastThreeArticles();

        // Récupération des collections de "Créateur choisi"
        $collectionsData = $collectionsModel->getCollectionsByCreator('belksasar3dprint');
        $collections = [];
        if ($collectionsData && isset($collectionsData['total_count']) && $collectionsData['total_count'] > 0) {
            // On garde uniquement les 4 premiers items
            if (isset($collectionsData['items'])) {
                $collectionsData['items'] = array_slice($collectionsData['items'], 0, 4);
            }
            $collections = $collectionsData;
        }

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
            'collections'     => $collections,
            'featuredProduct' => $featuredProduct,
            'featuredReview'  => $featuredReview,
            'isHome'          => $isHome
        ]);
    }
}
