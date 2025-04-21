<?php
namespace Figurine\Controller;

use Figurine\Lib\View;

class PageController {
    public function aboutUs() {
        View::render('about-us');
    }
}
?>