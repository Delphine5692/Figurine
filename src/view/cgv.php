<?php
$title = "Conditions Générales de Ventes - Throne Of Miniatures";
$description = "Consultez les Conditions Générales de Ventes de Throne Of Miniatures. Informations sur les modalités de vente et conditions d'achat.";
ob_start();
?>

<div class="container">
    <header>
        <h1 tabindex="0">Conditions Générales de Ventes</h1>
    </header>
    <article class="cgv">
        <section>
            <header>
                <h2 tabindex="0">Introduction</h2>
            </header>
            <p>
                Les présentes Conditions Générales de Vente (ci-après « CGV ») régissent l’ensemble des relations contractuelles entre le site Throne Of Miniatures, édité par Figurine, et toute personne physique ou morale (ci-après « le Client ») souhaitant effectuer un achat via le site internet www.figurine.fr (ci-après « le Site »).

                En accédant au Site et en procédant à une commande, le Client reconnaît avoir pris connaissance des présentes conditions et les accepter sans réserve. Les CGV sont accessibles à tout moment sur le Site et peuvent être mises à jour à tout moment par l’éditeur du site.
            </p>
        </section>
        <hr>
        <section>
            <header>
                <h2 tabindex="0">Objet</h2>
            </header>
            <p>
                Les présentes CGV ont pour but de définir les modalités d'achat des produits proposés sur notre boutique en ligne. Elles s'appliquent à toute commande passée sur le site, dans un esprit de confiance et de transparence.
            </p>
        </section>
        <hr>
        <section>
            <header>
                <h2 tabindex="0">Commandes</h2>
            </header>
            <p>
                Chaque commande est prise en compte dès sa validation. Une confirmation vous sera envoyée par email. En cas de doute ou de besoin spécifique, n'hésitez pas à nous contacter avant votre achat.
            </p>
        </section>
        <hr>
        <section>
            <header>
                <h2 tabindex="0">Tarifs</h2>
            </header>
            <p>
                Les prix affichés sont en euros, toutes taxes comprises. Nous nous réservons le droit de modifier les prix à tout moment, mais le tarif appliqué sera toujours celui indiqué au moment de l’achat.
            </p>
        </section>
        <hr>
        <section>
            <header>
                <h2 tabindex="0">Livraison</h2>
            </header>
            <p>
                Nous expédions avec soin chaque figurine depuis notre atelier. Les délais varient selon la destination, mais nous faisons de notre mieux pour une livraison rapide. Un numéro de suivi est fourni dès l’envoi.
            </p>
        </section>
        <hr>
        <section>
            <header>
                <h2 tabindex="0">Retours et réclamations</h2>
            </header>
            <p>
                Vous avez 14 jours après réception pour changer d’avis. Si un produit est endommagé ou ne correspond pas à votre commande, contactez-nous — nous trouverons une solution ensemble.
            </p>
        </section>
        <hr>
        <section>
            <header>
                <h2 tabindex="0">Données personnelles</h2>
            </header>
            <p>
                Nous respectons votre vie privée : vos données ne seront jamais revendues. Elles ne sont utilisées que pour le bon traitement de vos commandes.
            </p>
        </section>
        <hr>
        <section>
            <header>
                <h2 tabindex="0">Modifications</h2>
            </header>
            <p>
                Ces conditions peuvent être mises à jour, mais nous vous informerons toujours de tout changement important.
            </p>
        </section>
    </article>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>