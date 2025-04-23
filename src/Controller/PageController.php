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

    // Affiche la page des mentions légales
    public function legalNotices()
    {
        View::render('legal-notices');
    }

    // Affiche la page des Conditions Générales de Ventes
    public function cgv()
    {
        View::render('cgv');
    }

    // Affiche la page de la Politique de Confidentialité
    public function privacyPolicy()
    {
        View::render('privacy-policy');
    }

    // Affiche la page des Conditions de Ventes et Retours
    public function returns()
    {
        View::render('returns');
    }

    // Affiche la page de FAQ (Questions Fréquemment Posées)
    public function faq()
    {
        View::render('faq');
    }
}
