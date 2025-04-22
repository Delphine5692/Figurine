<?php
$title = "Mentions Légales - Figurine";
$description = "Les mentions légales du site Figurine. Informations sur la propriété, l'hébergement, la responsabilité, etc.";
ob_start();
?>

<header>
    <!-- Titre principal visible et accessible -->
    <h1 tabindex="0">Mentions Légales</h1>
</header>

<article class="legal-notices">
    <section>
            <h2 tabindex="0">Identification</h2>
        <p>
            Nom du site : Figurine<br>
            Propriétaire : Nom de l'entreprise<br>
            Adresse : 123 Rue Exemple, Ville, Pays<br>
            Email : <a href="mailto:contact@figurine.com">contact@figurine.com</a><br>
            Téléphone : +33 1 23 45 67 89
        </p>
    </section>

    <section>
            <h2 tabindex="0">Hébergement</h2>
        <p>
            Hébergeur : Nom de l’hébergeur<br>
            Adresse : 456 Avenue Hébergeur, Ville, Pays<br>
            Téléphone : +33 9 87 65 43 21<br>
            Site web : <a href="https://www.hebergeur.com" target="_blank" rel="noopener" aria-label="Site web de l'hébergeur">www.hebergeur.com</a>
        </p>
    </section>

    <section>
            <h2 tabindex="0">Responsabilité</h2>
        <p>
            Les informations fournies sur ce site sont à titre indicatif. Figurine ne saurait être tenue responsable des erreurs ou omissions éventuelles.
        </p>
    </section>

    <section>
            <h2 tabindex="0">Propriété Intellectuelle</h2>
        <p>
            Tous les contenus présents sur ce site, qu'il s'agisse de textes, images ou logos, sont protégés par le droit d'auteur. Toute reproduction sans autorisation préalable est interdite.
        </p>
    </section>
</article>

<?php
$content = ob_get_clean();
require SRC_DIR . '/view/layout.php';
?>