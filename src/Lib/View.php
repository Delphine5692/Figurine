<?php

namespace Figurine\Lib;

class View
{
    public static function render($view, $data = [])
    {
        extract($data); // Rend les clés du tableau disponibles comme variables dans la vue
        require SRC_DIR . "/view/{$view}.php";
    }
}