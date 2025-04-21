<?php
$title = "Détails d'un article";

ob_start();
?>

<article class="blog-detail container">
    <header class="blog-header">
        <h1><?= htmlspecialchars($article['titre']); ?></h1>
        <p class="blog-date">
            <strong>Date de publication :</strong> <?= htmlspecialchars(date("d/m/Y à H\hi", strtotime($article['date_publication']))); ?>
        </p>
    </header>

    <figure class="blog-figure">
        <img class="blog-image" src="public/uploads/<?= htmlspecialchars($article['image']); ?>" alt="Image de l'article <?= htmlspecialchars($article['titre']); ?>">
    </figure>

    <section class="blog-content">
        <p><?= nl2br(htmlspecialchars($article['description'])); ?></p>
    </section>

    <hr>
    <section class="blog-comments">
        <header class="comments-header">
            <h2>Commentaires</h2>
        </header>

        <?php if (count($comments) > 0): ?>
            <ul class="blog-comment-list">
                <?php foreach ($comments as $commentaire): ?>
                    <li class="blog-comment-item">
                        <p class="comment-meta">
                            <strong><?= htmlspecialchars($commentaire['nom']); ?></strong> le <?= htmlspecialchars(date("d/m/Y à H\hi", strtotime($commentaire['date_commentaire']))); ?>
                        </p>
                        <div class="comment-body">
                            <p><?= nl2br(htmlspecialchars($commentaire['msg_blog'])); ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucun commentaire pour cet article.</p>
        <?php endif; ?>
    </section>

    <?php if (isset($_SESSION['id_utilisateur'])): ?>
        <section class="blog-comment-form">
            <form action="commentaire" method="post">
                <input type="hidden" name="id_article" value="<?= htmlspecialchars($article['id_article']); ?>">
                <label for="contenu">Votre commentaire :</label>
                <textarea name="msg_blog" rows="4" cols="50" placeholder="Taper ici votre commentaire"></textarea><br>
                <button class="btn-primary" type="submit">Poster le commentaire</button>
            </form>
        </section>
    <?php else: ?>
        <p>Vous devez être connecté pour laisser un commentaire.</p>
    <?php endif; ?>

</article>

<?php
$content = ob_get_clean();
require SRC_DIR . '/view/layout.php';
?>