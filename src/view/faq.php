<?php
$title = "FAQ - Foire aux Questions - Mon Site";
$description = "Foire aux Questions concernant commande, paiement, livraison, produits, retours, échanges et informations sur Mon Site.";
ob_start();
?>

<div class="container">
    <header>
        <h1 tabindex="0">Foire aux Questions (FAQ)</h1>
    </header>

    <article class="faq">
        <section class="faq-category">
            <header>
                <h2 tabindex="0">Commande &amp; Paiement</h2>
            </header>
            <div class="faq-item">
                <h3>Comment puis-je passer commande ?</h3>
                <p>Il vous suffit de parcourir les figurines, d’ajouter vos coups de cœur au panier, puis de finaliser la commande via notre interface sécurisée.</p>
            </div>
            <div class="faq-item">
                <h3>Quels moyens de paiement acceptez-vous ?</h3>
                <p>Nous acceptons les paiements par carte bancaire (Visa, Mastercard), PayPal, et parfois d'autres selon les périodes (précisé lors du paiement).</p>
            </div>
            <div class="faq-item">
                <h3>Puis-je annuler ma commande ?</h3>
                <p>Tant que la commande n’a pas été préparée ou expédiée, vous pouvez demander une annulation. Contactez-nous rapidement via le formulaire de contact.</p>
            </div>
        </section>
<hr>
        <section class="faq-category">
            <header>
                <h2 tabindex="0">Livraison</h2>
            </header>
            <div class="faq-item">
                <h3>Quels sont les délais de livraison ?</h3>
                <p>L'expédition a généralement lieu sous 3 à 7 jours ouvrés. La livraison prend ensuite 2 à 5 jours selon votre adresse, et un numéro de suivi vous est envoyé.</p>
            </div>
            <div class="faq-item">
                <h3>Livrez-vous à l’international ?</h3>
                <p>Oui, nous livrons dans plusieurs pays d’Europe. Les frais et délais varient selon la destination.</p>
            </div>
        </section>
<hr>
        <section class="faq-category">
            <header>
                <h2 tabindex="0">Produits</h2>
            </header>
            <div class="faq-item">
                <h3>Vos figurines sont-elles peintes ?</h3>
                <p>Certaines figurines sont proposées brutes, d’autres pré-peintes ou peintes à la main. Cette information figure sur chaque fiche produit.</p>
            </div>
            <div class="faq-item">
                <h3>Puis-je commander une figurine personnalisée ?</h3>
                <p>Pas pour le moment, mais nous y réfléchissons ! Suivez-nous pour rester informé(e) des nouveautés.</p>
            </div>
        </section>
<hr>
        <section class="faq-category">
            <header>
                <h2 tabindex="0">Retours &amp; Échanges</h2>
            </header>
            <div class="faq-item">
                <h3>Et si je ne suis pas satisfait(e) ?</h3>
                <p>Vous disposez de 14 jours après réception pour retourner le produit dans son état d'origine. Consultez nos Conditions de Retour pour plus de détails.</p>
            </div>
            <div class="faq-item">
                <h3>Puis-je échanger une figurine ?</h3>
                <p>Les échanges directs ne sont pas possibles. Il convient de retourner le produit, puis de repasser commande.</p>
            </div>
        </section>
<hr>
        <section class="faq-category">
            <header>
                <h2 tabindex="0">Compte &amp; Données</h2>
            </header>
            <div class="faq-item">
                <h3>Comment modifier mes informations personnelles ?</h3>
                <p>Connectez-vous à votre espace Profil pour mettre à jour vos informations, supprimer un commentaire ou même votre compte.</p>
            </div>
            <div class="faq-item">
                <h3>Et mes données personnelles ?</h3>
                <p>Elles sont utilisées uniquement dans le cadre de votre commande. Pour en savoir plus, consultez notre Politique de Confidentialité.</p>
            </div>
        </section>
<hr>
        <section class="faq-category">
            <header>
                <h2 tabindex="0">Autres questions</h2>
            </header>
            <div class="faq-item">
                <h3>Je suis peintre ou créateur. Puis-je proposer mes figurines ?</h3>
                <p>Nous serions ravis d'en discuter ! Contactez-nous avec vos références et projets.</p>
            </div>
            <div class="faq-item">
                <h3>Comment vous contacter ?</h3>
                <p>Via notre page Contact ou par mail. Nous nous efforçons de répondre sous 48h.</p>
            </div>
        </section>
    </article>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>