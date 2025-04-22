<footer>
    <p>&copy; <?= date('Y'); ?> Mon Site. Tous droits réservés.</p>
    <li><a href="legal-notices">Mentions Légales</a></li>
</footer>

<script src="public/js/script.js"></script>
<script src="public/js/collection.js"></script>

<?php if (isset($includeAjouterPanierScript) && $includeAjouterPanierScript): ?>
    <script src="public/js/ajouterPanier.js"></script>
<?php endif; ?>

<?php if (isset($includePanierScript) && $includePanierScript): ?>
    <script src="public/js/panier.js"></script>
    <script>
        showCart(); // Appeler la fonction pour afficher le panier
    </script>
<?php endif; ?>

<script>
    // La pop-up disparaît après 5 secondes
    setTimeout(function() {
        const container = document.getElementById('flash-container');
        if (container) {
            container.style.opacity = '0';
            setTimeout(function() {
                container.remove();
            }, 500);
        }
    }, 5000);
</script>