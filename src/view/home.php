<?php
$title = "Accueil";

ob_start();
?>

<!-- Bloc d'introduction personnalisé -->
<div class="intro-section">
  <div class="intro-text">
    <h1>Vivez l'expérience</h1>
    <p>Plongez dans l'univers unique de nos figurines, alliant art et savoir-faire.</p>
    <a href="products" class="btn-primary">Découvrez nos figurines</a>
  </div>
  <div class="intro-image">
    <img src="public/images/figurine.png" alt="Figurine artisanale représentant un immense squelette au dessus d'un trône sur lequel est assis un chevalier en armure. La couleur bleu est dominante.">
  </div>
</div>

<?php
$subHeader = ob_get_clean();

ob_start();
?>

<!-- Section "Nos dernières figurines" -->
<section class="latest-figures-section">
  <h2>Nos dernières figurines</h2>
  <div class="figures-grid">
    <?php if (!empty($products)): ?>
      <?php foreach ($products as $product): ?>
      <article class="figure-card">
        <img class="figure-image" src="public/uploads/<?php echo $product['image_1']; ?>" alt="Figurine : <?php echo htmlspecialchars($product['nom']); ?>">
        <div class="figure-info">
          <h3><?php echo htmlspecialchars($product['nom']); ?></h3>
          <p><?php echo number_format($product['prix'], 2, ',', ' '); ?> €</p>
          <a href="product/<?php echo $product['id_produit']; ?>" class="btn-detail">Voir le détail</a>
        </div>
      </article>
      <?php endforeach; ?>
    <?php else: ?>
      <p>Aucun produit récent disponible.</p>
    <?php endif; ?>
  </div>
</section>

<!-- PRODUIT MIS EN AVANT EN FONCTION DU NOMBRE D'AVIS -->

<div class="featured-container">
  <hr>
  <h1>Zoom sur ...</h1>
  <?php if (isset($featuredProduct)): ?>
    <div class="featured-product">
      <!-- Colonne Image -->
      <div class="featured-image">
        <img src="public/uploads/<?php echo htmlspecialchars($featuredProduct['image_1']); ?>" alt="Figurine la plus commenté du site : <?php echo htmlspecialchars($featuredProduct['nom']); ?>">
      </div>
      <!-- Colonne Informations -->
      <div class="featured-info">
        <h2><?php echo htmlspecialchars($featuredProduct['nom']); ?></h2>
        <p class="price"><?php echo number_format($featuredProduct['prix'], 2, ',', ' '); ?> €</p>
        <p><?php echo substr($featuredProduct['description'], 0, 150); ?>...</p>
        <a href="product/<?php echo $featuredProduct['id_produit']; ?>" class="btn-card">Voir la figurine</a>
        <?php if (isset($featuredReview)): ?>
          <div class="featured-review">
            <p>Dernier avis :</p>
            <blockquote>
              "<?php echo htmlspecialchars($featuredReview['contenu']); ?>"
            </blockquote>
            <p class="review-author">par <?php echo htmlspecialchars($featuredReview['auteur']); ?></p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  <?php else: ?>
    <p>Aucun produit mis en avant pour le moment.</p>
  <?php endif; ?>
</div>

<!-- Section "Nos derniers articles" -->
<section class="latest-articles-section">
  <hr>
  <h2>Nos derniers articles de blog</h2>
  <?php if (count($articles) > 0): ?>
    <div class="articles-grid">
      <?php foreach ($articles as $article): ?>
        <article class="article-card">
          <img class="article-image" src="public/uploads/<?php echo $article['image']; ?>" alt="Article : <?php echo htmlspecialchars($article['titre']); ?>">
          <div class="article-info">
            <h3><?php echo $article['titre']; ?></h3>
            <p><?php echo substr($article['description'], 0, 150); ?>...</p>
            <a href="article/<?php echo $article['id_article']; ?>" class="btn-detail">Voir plus</a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p>Aucun article disponible pour le moment.</p>
  <?php endif; ?>
</section>

<hr>
<!-- Section "Nos collections" -->
<section class="collections-section">
  <h1>Envie d'autre chose ?</h1>
  <h2>Découvrez le travail de <strong>"Belksasar"</strong></h2>
  <div class="collections-grid">
    <?php if (isset($collections['total_count']) && $collections['total_count'] > 0 && !empty($collections['items'])): ?>
      <?php foreach ($collections['items'] as $item): ?>
        <div class="collection-card">
          <a href="<?php echo htmlspecialchars($item['url']); ?>" target="_blank" rel="noopener noreferrer" onclick="return confirm('Attention, vous quittez Throne Of Miniatures');">
            <?php
            $imgUrl = isset($item['obj_img'][0]) ? $item['obj_img'][0] : 'public/images/default.png';
            ?>
            <img src="<?php echo htmlspecialchars($imgUrl); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
          </a>
          <p>Créateur : <?php echo htmlspecialchars($item['owner']['name']); ?></p>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>Aucune collection trouvée.</p>
    <?php endif; ?>
  </div>
</section>

<?php
$content = ob_get_clean();
require SRC_DIR . '/view/layout.php';
?>