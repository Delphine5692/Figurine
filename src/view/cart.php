<?php
global $baseUrl;

$includePanierScript = true;
$title = "Mon Panier";

ob_start();
?>

<div class="container">
<section class="cart container">
    <header class="cart-header">
      <h1>Mon Panier</h1>
    </header>

    <div id="panier-container" class="cart-items"></div>

    <div class="cart-summary">
      <h2 id="total-prix"></h2>
      <div class="cart-actions">
        <button class="btn-empty" id="vider-panier">Vider le panier</button>
        <button class="btn-add" id="valider-panier">Valider le panier</button>
      </div>
    </div>
  </section>
</div>

<?php
$content = ob_get_clean();
require SRC_DIR . '/view/layout.php';
?>