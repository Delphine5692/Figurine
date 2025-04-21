<?php
global $baseUrl;

$includeAjouterPanierScript = true;
$title = "Details des produits";
$description = "Découvrez les détails de chaque produit, y compris les avis des clients et les options d'achat.";

ob_start();
?>

<div class="container">

<article class="product-detail">
      <h1 itemprop="name"><?php echo htmlspecialchars($product['nom']); ?></h1>

    <div class="product-layout">
      <!-- Galerie d'images -->
      <section class="gallery-block">
        <figure class="main-image">
          <img id="mainImage" class="image-article" src="public/uploads/<?php echo htmlspecialchars($product['image_1']); ?>" 
               alt="Image du produit : <?php echo htmlspecialchars($product['nom']); ?>" itemprop="image">
        </figure>
        <aside class="thumbnails">
          <?php if (!empty($product['image_2'])): ?>
            <img class="thumbnail" src="public/uploads/<?php echo htmlspecialchars($product['image_2']); ?>" 
                 alt="Image 2 du produit" data-image="public/uploads/<?php echo htmlspecialchars($product['image_2']); ?>">
          <?php endif; ?>
          <?php if (!empty($product['image_3'])): ?>
            <img class="thumbnail" src="public/uploads/<?php echo htmlspecialchars($product['image_3']); ?>" 
                 alt="Image 3 du produit" data-image="public/uploads/<?php echo htmlspecialchars($product['image_3']); ?>">
          <?php endif; ?>
        </aside>
      </section>

      <!-- Bloc 2 : Informations produit -->
      <section class="info-block">
        <div class="description" itemprop="description">
          <h2>Description</h2>
          <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
        </div>
        <div class="details">
          <h2>Détails du produit</h2>
          <p>Taille : <?php echo htmlspecialchars($product['taille']); ?> cm</p>
        </div>
        <div class="purchase-info">
          <p class="price">
            Prix :
            <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
              <span itemprop="price"><?php echo htmlspecialchars(number_format($product['prix'], 2, ',', ' ')); ?></span>
              <span itemprop="priceCurrency" content="EUR">€</span>
            </span>
          </p>
          <button class="add-to-cart btn-list" data-id="<?php echo $product['id_produit']; ?>"
            data-nom="<?php echo htmlspecialchars($product['nom']); ?>"
            data-prix="<?php echo htmlspecialchars($product['prix']); ?>">
            Ajouter au panier
          </button>
        </div>
      </section>

    </div>

    <section class="reviews">
        <hr>
        <h2>Avis sur ce produit</h2>
        <div class="reviews-grid">
            <?php if (isset($reviews) && is_array($reviews) && count($reviews) > 0): ?>
            <?php foreach ($reviews as $review): ?>
                <article class="review">
                <p class="review-meta">
                    <strong><?php echo htmlspecialchars($review['auteur']); ?></strong> le <?php echo date("d/m/Y \\à H\\hi", strtotime($review['date_review'])); ?>
                </p>
                <p class="review-content"><?php echo nl2br(htmlspecialchars($review['contenu'])); ?></p>
                </article>
            <?php endforeach; ?>
            <?php else: ?>
            <p class="no-reviews">Aucun avis pour le moment.</p>
            <?php endif; ?>
        </div>

        <?php if (isset($_SESSION['id_utilisateur'])): ?>
            <form action="productReview" method="POST" class="review-form">
            <input type="hidden" name="id_produit" value="<?php echo htmlspecialchars($product['id_produit']); ?>">
            <div class="form-group">
                <label for="contenu">Votre avis :</label>
                <textarea name="contenu" id="contenu" rows="4" placeholder="Taper ici votre avis" required></textarea>
            </div>
            <button type="submit" class="btn-primary">Poster mon avis</button>
            </form>
        <?php else: ?>
            <p class="login-note">Vous devez être connecté pour poster un avis.</p>
        <?php endif; ?>
    </section>
</article>
  
</div>

<?php
$content = ob_get_clean();
require SRC_DIR . '/view/layout.php';
?>