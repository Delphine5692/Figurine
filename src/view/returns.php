<?php
$title = "Conditions de Ventes et Retours - Throne Of Miniatures";
$description = "Informations sur les conditions de ventes et la procédure de retours de produits sur Throne of Miniatures.";
ob_start();
?>

<div class="container">
    <header>
        <h1 tabindex="0">Conditions de Ventes et Retours</h1>
    </header>
    <article class="returns">
        <section>
            <header>
                <h2 tabindex="0">Procédure de Retour</h2>
            </header>
            <p>
                Si vous n'êtes pas pleinement satisfait·e de votre achat, vous disposez d’un délai de 14 jours à compter de la réception du produit pour effectuer un retour.
                Le produit doit être renvoyé dans son état d’origine, non utilisé et dans son emballage d'origine. Les frais de retour sont à votre charge, sauf en cas d’erreur de notre part ou de produit défectueux.

                Avant tout retour, merci de nous contacter via le formulaire de contact ou par email afin que nous puissions vous indiquer la marche à suivre.
            </p>
        </section>
        <hr>
        <section>
            <header>
                <h2 tabindex="0">Modalités de Vente</h2>
            </header>
            <p>
                Les produits proposés sur le site sont réalisés à la main ou imprimés à la demande. Chaque pièce peut présenter de légères variations qui font partie de leur caractère artisanal.
                Les commandes sont validées une fois le paiement confirmé.
                Nous nous réservons le droit d’annuler une commande en cas de rupture de stock ou de problème technique.
                En achetant sur notre site, vous acceptez ces conditions et reconnaissez avoir pris connaissance de nos politiques.
            </p>
        </section>
        <hr>
        <section>
            <header>
                <h2 tabindex="0">Délais de Livraison</h2>
            </header>
            <p>
            Les commandes sont généralement expédiées sous 3 à 7 jours ouvrés, selon le volume de la demande et le niveau de personnalisation de votre commande.
            Une fois expédiée, la livraison prend entre 2 à 5 jours ouvrés selon votre lieu de résidence. Un numéro de suivi vous sera communiqué dès l’envoi.
            </p>
        </section>
        <hr>
        <section>
            <header>
                <h2 tabindex="0">Échanges Possibles</h2>
            </header>
            <p>
            Nous ne proposons pas d’échanges automatiques.
            Si vous souhaitez un autre modèle ou une autre version, il vous faudra effectuer un retour puis passer une nouvelle commande. Bien entendu, nous sommes à votre disposition pour vous conseiller ou vous assister dans cette démarche.
            </p>
        </section>
    </article>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>