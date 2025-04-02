<?php

namespace Figurine\Controller;

use Figurine\model\Produit;

class ProduitController
{
    public function afficherProduits()
    {
// rien de special à faire avant de demander à la base
        // sinon code ICI (du genre récupération des paramètres, ...)
        // ...

        // utilisation de la méthode getAllProduits() de classe de la classe Produit
        $produits = Produit::getAllProduits();

        // $produits = null si la liste est vide, mais la vue peut gérer cela et s'adapter en conséquence
        // On peut aussi tester si $produits == null à ce niveau et décider d'aller vers une autre page (tout est possible, ce sont tes choix d'UX)

        // Inclure la vue qui va exploiter la variable $produits qui vient d'être renseignée
        require_once 'src/view/view-produit.php';

    }
}
