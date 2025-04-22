<?php

namespace Figurine\Lib;

class View
{
    /**
     * Affiche la vue spécifiée.
     *
     * - Extrait le tableau associatif $data afin de rendre ses clés accessibles comme variables dans la vue.
     * - Inclut le fichier de vue correspondant situé dans le répertoire défini par SRC_DIR.
     *
     * @param string $view Le nom du fichier de vue à afficher (sans l'extension).
     * @param array $data (optionnel) Données supplémentaires à rendre disponibles dans la vue.
     * @return void
     */
    public static function render($view, $data = [])
    {
        extract($data); // Rend les clés du tableau disponibles comme variables dans la vue.
        require SRC_DIR . "/view/{$view}.php"; // Charge le fichier de vue correspondant.
    }
}
?>