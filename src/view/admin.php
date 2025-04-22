<?php
$title = "Inscription";

ob_start();
?>

<header class="admin-header">
    <h1>Tableau de bord administrateur</h1>
    <p class="welcome-user">Bonjour, <?= htmlspecialchars($_SESSION['prenom']); ?> !</p>
</header>

<hr>
<div class="admin-add-container">
    <section class="admin-add-article" aria-labelledby="add-article-title">
        <h2 id="add-article-title">Ajouter un article</h2>
        <form action="article/ajouter-article" method="POST" enctype="multipart/form-data">
            <!-- contenu du formulaire article -->
            <div>
                <label for="titre">Titre :</label>
                <input type="text" id="titre" name="titre" required>
            </div>
            <div>
                <label for="description">Description :</label>
                <textarea id="description" name="description" rows="5" required></textarea>
            </div>
            <div>
                <label for="image">Image :</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>
            <button type="submit">Ajouter l'article</button>
        </form>
    </section>

    <section class="admin-add-product" aria-labelledby="add-product-title">
        <h2 id="add-product-title">Ajouter un produit</h2>
        <form action="product/ajouter-produit" method="POST" enctype="multipart/form-data">
            <!-- contenu du formulaire produit -->
            <div>
                <label for="nom">Nom du produit :</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            <div>
                <label for="description-produ">Description :</label>
                <textarea id="description-produ" name="description" rows="5" required></textarea>
            </div>
            <div>
                <label for="prix">Prix (en €) :</label>
                <input type="number" id="prix" name="prix" step="0.01" required>
            </div>
            <div>
                <label for="taille">Taille (en cm) :</label>
                <input type="text" id="taille" name="taille">
            </div>
            <div>
                <label for="image_1">Image :</label>
                <input type="file" id="image_1" name="image" accept="image/*" required>
            </div>
            <fieldset>
  <legend>Catégories</legend>
  <?php foreach ($categories as $category): ?>
    <div>
      <input type="checkbox" id="categorie-<?= htmlspecialchars($category['id_categorie']) ?>" name="categories[]" value="<?= htmlspecialchars($category['id_categorie']) ?>">
      <label for="categorie-<?= htmlspecialchars($category['id_categorie']) ?>">
        <?= htmlspecialchars($category['nom']) ?>
      </label>
    </div>
  <?php endforeach; ?>
</fieldset>
            <button type="submit">Ajouter le produit</button>
        </form>
    </section>
</div>

<hr>
<section class="admin-users" aria-labelledby="users-title">
    <h2 id="users-title">Gestion des utilisateurs</h2>
    <p>Nombre d'utilisateurs : <?= count($users); ?></p>
    <p>Nombre de commentaires : <?= count($comments); ?></p>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <?php if (!empty($users)): ?>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id_utilisateur']); ?></td>
                        <td><?= htmlspecialchars($user['nom']); ?></td>
                        <td><?= htmlspecialchars($user['mail']); ?></td>
                        <td><?= htmlspecialchars($user['statut']); ?></td>
                        <td>
                            <?php if (!isset($user['statut']) || $user['statut'] !== 'supprimer'): ?>
                                <form action="admin/supprimer-utilisateur" method="POST" style="display:inline-block;" onsubmit="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">
                                    <input type="hidden" name="id_utilisateur" value="<?= htmlspecialchars($user['id_utilisateur']); ?>">
                                    <button class="btn-supp" type="submit">Supprimer</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        <?php else: ?>
            <tfoot>
                <tr>
                    <td colspan="5">Aucun utilisateur trouvé.</td>
                </tr>
            </tfoot>
        <?php endif; ?>
    </table>
</section>

<?php
$content = ob_get_clean();
require SRC_DIR . '/view/layout.php';
?>