<?php
global $baseUrl;

$title = "Liste des articles de blog";

ob_start();
?>

<div class="container">

  <section class="articles container">
    <h1>Découvrez nos derniers articles de blog</h1>

    <?php if (count($articles) > 0): ?>
      <div class="articles-grid">
        <?php foreach ($articles as $article): ?>
          <article class="article-card">
            <figure class="article-figure">
              <img class="article-image" src="public/uploads/<?php echo htmlspecialchars($article['image']); ?>" alt="Image de l'article <?php echo htmlspecialchars($article['titre']); ?>">
            </figure>
            <div class="article-content">
              <h2><?php echo htmlspecialchars($article['titre']); ?></h2>
              <p><?php echo nl2br(htmlspecialchars(substr($article['description'], 0, 100))); ?>...</p>
              <a href="article/<?php echo $article['id_article']; ?>" class="btn">Voir plus</a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p>Aucun article disponible.</p>
    <?php endif; ?>
  </section>

  <hr>

  <section class="tutorial">
    <h2>Tutoriel de : OrigoWorld3D</h2>
    <div class="video-presentation">
      <iframe src="https://www.youtube.com/embed/sgUK_gED8kw?si=7LQL5CyI5aRFRrB_" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    </div>
    <!-- 
        <video controls width="640" poster="vignette-aperçu.svg">
          <source src="public/videos/figurine.mp4" type="video/mp4">
          Votre navigateur ne supporte pas la vidéo HTML5.
        </video> 
    -->
    <div class="video-description">
      <p>Je te présente aujourd'hui le matériel idéal pour commencer la peinture de tes impressions 3D résine. Etant débutant et ayant récemment acheter un grand nombre de produits pour commencer la mise en peinture de mes figurines, je souhaitais te partager mon expérience en présentant les produits faciles à utiliser et qui m’ont grandement facilité la tâche pour mes premiers pas. J’espère que le résultat final de ma première mise en peinture sur cette figurine de Bayonetta 3 vous plaira. Bon visionnage !</p>
    </div>
  </section>

</div>

<?php
$content = ob_get_clean();
require SRC_DIR . '/view/layout.php';
?>