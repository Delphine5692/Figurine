<?php
global $baseUrl;

$title = "Mon Profil";
$description = "Gérez votre compte, vos commandes, vos commentaires et vos avis sur Figurine.";

ob_start();
?>

<!-- Section Profil -->
<section class="profile container">
  <h1>Mon profil</h1>
  <p class="welcome-user">
    Bonjour <?= isset($_SESSION['prenom']) ? htmlspecialchars($_SESSION['prenom']) : ''; ?> <?= isset($_SESSION['nom']) ? htmlspecialchars($_SESSION['nom']) : ''; ?> !
  </p>

  <!-- Adresse postale -->
  <article class="profile-address" aria-labelledby="adresse-heading">
    <h2 id="adresse-heading">Mon adresse postale</h2>
    <div class="card">
      <?php if (!empty($user['adresse'])): ?>
        <p><?= nl2br(htmlspecialchars($user['adresse'])); ?></p>
        <form action="" method="GET">
          <input type="hidden" name="url" value="profil">
          <input type="hidden" name="action" value="modifier-adresse">
          <button type="submit">Modifier l'adresse</button>
        </form>
      <?php else: ?>
        <p>Vous n'avez pas encore ajouté d'adresse postale.</p>
        <form action="index.php" method="GET">
          <input type="hidden" name="url" value="profil">
          <input type="hidden" name="action" value="ajouter-adresse">
          <button type="submit">Ajouter une adresse</button>
        </form>
      <?php endif; ?>
    </div>
    <?php if (isset($_GET['action']) && ($_GET['action'] === 'modifier-adresse' || $_GET['action'] === 'ajouter-adresse')): ?>
      <form action="index.php?url=modifier-adresse" method="POST">
        <label for="adresse">Adresse postale</label>
        <textarea id="adresse" name="adresse" rows="4" cols="50" placeholder="Votre adresse postale"><?= isset($user['adresse']) ? htmlspecialchars($user['adresse']) : ''; ?></textarea>
        <button type="submit">Enregistrer l'adresse</button>
      </form>
    <?php endif; ?>
  </article>

  <hr>
  <!-- Historique de commandes -->
  <article class="profile-orders" aria-labelledby="orders-heading">
    <h2 id="orders-heading">Mon historique de commandes</h2>
    <?php if (count($orders) > 0): ?>
      <div class="order-container">
        <?php foreach ($orders as $order): ?>
          <article class="card order-card">
            <h3>Commande n°<?= htmlspecialchars($order['id_commande']); ?></h3>
            <p><strong>Date :</strong> <?= htmlspecialchars(date("d/m/Y à H\hi", strtotime($order['date_commande']))); ?></p>
            <p><strong>Statut :</strong> <?= htmlspecialchars($order['statut']); ?></p>
            <p><strong>Total :</strong> <?= htmlspecialchars(number_format($order['total_commande'], 2)); ?> €</p>
            <?php if (!empty($order['produits'])): ?>
              <ul>
                <?php foreach ($order['produits'] as $product): ?>
                  <li>
                    <img src="public/uploads/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['nom']); ?>">
                    <span><?= htmlspecialchars($product['nom']); ?> x<?= htmlspecialchars($product['quantite']); ?></span>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
          </article>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p>Vous n'avez pas encore passé de commande.</p>
    <?php endif; ?>
  </article>

  <hr>
  <!-- Commentaires -->
  <article class="profile-comments" aria-labelledby="comments-heading">
    <h2 id="comments-heading">Mes commentaires</h2>
    <?php if (count($comments) > 0): ?>
      <div class="comment-container">
        <?php foreach ($comments as $comment): ?>
          <article class="card">
            <h3><?= htmlspecialchars($comment['titre']); ?></h3>
            <p><?= nl2br(htmlspecialchars($comment['msg_blog'])); ?></p>
            <p><em>Publié le : <?= htmlspecialchars(date("d/m/Y à H\hi", strtotime($comment['date_commentaire']))); ?></em></p>
            <form action="supprimer-commentaire" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?');">
              <input type="hidden" name="id_commentaire" value="<?= htmlspecialchars($comment['id_commentaire']); ?>">
              <button type="submit">Supprimer</button>
            </form>
          </article>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p>Vous n'avez pas encore laissé de commentaire.</p>
    <?php endif; ?>
  </article>

  <hr>
  <!-- Avis -->
  <article class="profile-reviews" aria-labelledby="reviews-heading">
    <h2 id="reviews-heading">Mes avis</h2>
    <?php if (count($reviews) > 0): ?>
      <div class="review-container">
        <?php foreach ($reviews as $review): ?>
          <article class="card">
            <h3><?= htmlspecialchars($review['nom']); ?></h3>
            <p><?= nl2br(htmlspecialchars($review['contenu'])); ?></p>
            <p><em>Publié le : <?= htmlspecialchars(date("d/m/Y à H\hi", strtotime($review['date_review']))); ?></em></p>
            <form action="supprimer-avis" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet avis ?');">
              <input type="hidden" name="id_avis" value="<?= htmlspecialchars($review['id_avis']); ?>">
              <button type="submit">Supprimer</button>
            </form>
          </article>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p>Vous n'avez pas encore laissé d'avis.</p>
    <?php endif; ?>
  </article>

  <hr>
  <!-- Suppression du compte -->
  <section class="profile-delete" aria-labelledby="delete-heading">
    <h2 id="delete-heading">Supprimer mon compte</h2>
    <div class="card">
      <?php if (!empty($user['date_creation'])): ?>
        <p><em>Compte créé le : <?= htmlspecialchars(date("d/m/Y à H\hi", strtotime($user['date_creation']))); ?></em></p>
      <?php endif; ?>
      <form action="supprimer-compte" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.')">
        <button type="submit" class="btn btn-danger">Supprimer mon compte</button>
      </form>
    </div>
  </section>
</section>

<?php
$content = ob_get_clean();
require SRC_DIR . '/view/layout.php';
?>