<?php
global $baseUrl;

$includeAjouterPanierScript = true;
$title = "Liste des produits";

ob_start();
?>

<div class="container-products">

    <h1>Liste des Produits</h1>

    <!-- Formulaire de filtrage par catégorie -->
    <form method="GET" action="products">
        <label for="categorie">Filtrer par catégorie :</label>
        <select name="categorie" id="categorie" onchange="this.form.submit();">
            <option value="">Toutes les catégories</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= htmlspecialchars($category['id_categorie']); ?>"
                    <?php if (isset($_GET['categorie']) && $_GET['categorie'] == $category['id_categorie']) echo 'selected'; ?>>
                    <?= htmlspecialchars($category['nom']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <section class="products-grid">
        <?php if (empty($products)): ?>
            <p>Aucun produit dans cette catégorie</p>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <article class="product-card">
                    <figure>
                        <img src="public/uploads/<?php echo htmlspecialchars($product['image_1']); ?>" alt="Produit : <?php echo htmlspecialchars($product['nom']); ?>">
                    </figure>
                    <div class="card-text">
                        <h2><?php echo htmlspecialchars($product['nom']); ?></h2>
                        <p><?php echo htmlspecialchars(number_format($product['prix'], 2, ',', ' ')); ?> €</p>
                        <div class="card-buttons">
                            <button class="add-to-cart btn-list" data-id="<?php echo $product['id_produit']; ?>"
                                data-nom="<?php echo htmlspecialchars($product['nom']); ?>"
                                data-prix="<?php echo htmlspecialchars($product['prix']); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-basket2-fill" viewBox="0 0 16 16">
                                    <path d="M5.929 1.757a.5.5 0 1 0-.858-.514L2.217 6H.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h.623l1.844 6.456A.75.75 0 0 0 3.69 15h8.622a.75.75 0 0 0 .722-.544L14.877 8h.623a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1.717L10.93 1.243a.5.5 0 1 0-.858.514L12.617 6H3.383zM4 10a1 1 0 0 1 2 0v2a1 1 0 1 1-2 0zm3 0a1 1 0 0 1 2 0v2a1 1 0 1 1-2 0zm4-1a1 1 0 0 1 1 1v2a1 1 0 1 1-2 0v-2a1 1 0 0 1 1-1" />
                                </svg>
                            </button>
                            <!-- Lien pour voir le détail du produit -->
                            <a href="product/<?php echo $product['id_produit']; ?>" class="btn">Détail</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

</div>

<?php
$content = ob_get_clean();
require SRC_DIR . '/view/layout.php';
?>