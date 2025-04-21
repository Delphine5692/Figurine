<?php
$title = "À propos de moi";
ob_start();
?>

<section class="about-us">
    <h1>À propos..</h1>
    <article class="about-description">
        <p>Bienvenue dans un univers façonné par la passion et l’art. Ici, chaque figurine raconte une histoire, gravée dans la finesse de la résine et sublimée par des coups de pinceaux délicats. Nos créations, nées de l'imagination et sculptées avec précision, prennent vie entre vos mains, prêtes à peupler vos mondes fantastiques ou orner vos espaces.</p>

        <p>Chaque détail, chaque nuance est le fruit d’un artisanat minutieux, où l’âme de l’artiste s’exprime dans chaque courbe et couleur. Ce site est plus qu’une boutique : c’est une galerie d’émotions, une invitation à explorer la beauté miniature et l’excellence du fait-main.</p>

        <p>Laissez-vous transporter dans cet univers de rêves solides, où la résine devient poésie et chaque figurine une œuvre d’art prête à enrichir votre collection.</p>
    </article>
</section>

<hr>
<section class="about-extra">
    <h1>En savoir plus sur moi</h1>
    <div class="about-extra-content">
        <div class="about-extra-image">
            <img src="public/images/eve.png" alt="Représente la tête d'un corbeau noir à l'intérieur d'une pomme de couleur rouge.">
        </div>
        <div class="about-extra-text">
            <p>Je m’appelle Ève, et j’ai repris la peinture sur figurines en 2021, après plusieurs années d’arrêt. Ce retour s’est fait un peu par hasard, mais il m’a rapidement reconnectée à une passion profonde que j’avais un peu oubliée. </p>

            <p>Ce que j’aime dans la peinture sur figurines, c’est le mélange entre minutie et imagination. Chaque pièce est une histoire à raconter, un personnage à révéler. Depuis ma reprise, j’ai redécouvert le plaisir de travailler les couleurs, les textures, les ombrages... et surtout, de voir une figurine prendre vie sous mes pinceaux.</p>

            <p>Je suis encore en constante exploration : je teste, je rate parfois, j’apprends toujours. J’aime aussi beaucoup échanger avec d’autres passionnés, partager mes petits progrès et m’inspirer de ce que fait la communauté.</p>

            <p>Aujourd’hui, cette passion fait partie intégrante de mon quotidien. Elle m’apporte de la concentration, de la créativité, et surtout beaucoup de plaisir.</p>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
require SRC_DIR . '/view/layout.php';
?>