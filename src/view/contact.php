<?php 
$title = "Connexion";

ob_start();
?>

<section class="contact-page">
  <h1>Contactez-nous</h1>
  <p>Pour toute question ou suggestion, veuillez remplir le formulaire ci-dessous.</p>
  <form action="/contact/send" method="post" class="contact-form">
      <label for="email">Votre adresse email :</label>
      <input type="email" name="email" id="email" required>
      
      <label for="message">Votre message :</label>
      <textarea name="message" id="message" rows="5" required></textarea>
      
      <button type="submit">Envoyer</button>
  </form>
</section>

<?php
$content = ob_get_clean();
require SRC_DIR . '/view/layout.php';
?>