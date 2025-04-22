<?php

namespace Figurine\Controller;

use Figurine\Lib\View;

/**
 * Contrôleur des pages statiques.
 */
class PageController {

    /**
     * Affiche la page "À propos".
     *
     * Utilise la bibliothèque View pour rendre la vue correspondante
     */
    public function aboutUs() {
        View::render('about-us');
    }
}
?>