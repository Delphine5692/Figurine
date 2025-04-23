<?php
$title = "Politique de Confidentialité - Throne Of Miniatures";
$description = "Découvrez la politique de confidentialité de Throne Of Miniatures concernant la collecte et l'utilisation de vos données personnelles.";
ob_start();
?>

<div class="container">
    <header>
        <h1 tabindex="0">Politique de Confidentialité</h1>
    </header>
    <article class="privacy-policy">
        <section>
            <header>
                <h2 tabindex="0">Collecte d'Informations</h2>
            </header>
            <p>
                Lorsque vous naviguez sur notre site ou passez une commande, nous collectons certaines données nécessaires au bon fonctionnement de notre boutique. Cela inclut par exemple votre nom, votre adresse email, votre adresse de livraison et, éventuellement, des préférences liées à votre compte client.
            </p>
        </section>
        <hr>
        <section>
            <header>
                <h2 tabindex="0">Utilisation des Données</h2>
            </header>
            <p>Ces informations nous permettent de :</p>
            <ul>
                <li>- Traiter et suivre vos commandes,</li>
                <li>- Personnaliser votre expérience utilisateur,</li>
                <li>- Vous contacter en cas de besoin (problème de livraison, retour, etc.),</li>
                <li>- Améliorer le site et nos services de manière générale.</li>
            </ul>
            <p>
                Soyez rassuré·e : nous n'utilisons vos données que dans le cadre de notre relation client.
            </p>
        </section>
        <hr>
        <section>
            <header>
                <h2 tabindex="0">Sécurité</h2>
            </header>
            <p>
                Vos données sont traitées avec respect et confidentialité. Nous mettons en place les mesures techniques nécessaires pour les protéger contre toute perte, accès non autorisé ou mauvaise utilisation.
            </p>
        </section>
        <hr>
        <section>
            <header>
                <h2 tabindex="0">Partage des données</h2>
            </header>
            <p>
                Nous ne vendons ni ne louons vos données. Elles ne sont partagées qu’avec les prestataires de confiance qui nous aident à gérer les livraisons ou les paiements, et uniquement dans ce cadre précis.
            </p>
        </section>
        <hr>
        <section>
            <header>
                <h2 tabindex="0">Vos droits</h2>
            </header>
            <p>Depuis votre espace Profil, vous pouvez :</p>
            <ul>
                <li>- Modifier vos informations personnelles,</li>
                <li>- Supprimer vos commentaires et avis,</li>
                <li>- Supprimer votre compte à tout moment.</li>
            </ul>
            <p>
                Cependant, si vous avez déjà passé une commande, certaines données
                (nom, prénom, adresse, historique de commande) seront conservées
                pour des raisons légales, notamment comptables et d’assurance.
                Ces données ne seront ni utilisées à d'autres fins,
                ni partagées hors de ce cadre.
            </p>
        </section>
    </article>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>