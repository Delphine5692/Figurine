<?php

namespace Figurine\Controller;

// use Figurine\model\Produit;

class ProduitController
{
    public function afficherProduits()
    {



        // Inclure la vue et passer les produits
        require_once 'src/view/view-produit.php';
    }
}
