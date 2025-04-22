<?php

namespace Figurine\Controller;

use Figurine\Lib\View;

/**
 * Contrôleur des pages statiques.
 */
class PageController
{

    // Affiche la page A propos
    public function aboutUs()
    {
        View::render('about-us');
    }

    // Affiche le formulaire de contact
    public function showContact()
    {
        View::render('contact');
    }

    // Affiche le formulaire de contact
    public function legalNotices()
    {
        View::render('legal-notices');
    }
}
