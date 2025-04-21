<?php
$title = "Connexion - Figurine";
$description = "Connectez-vous pour accéder à votre compte et découvrir nos produits artisanaux.";

ob_start();
?>

<section class="login-container">
    <header>
        <h1>Connexion</h1>
    </header>
    <!-- Formulaire de connexion -->
    <form class="login" action="login" method="POST" aria-label="Formulaire de connexion">
            <legend class="visually-hidden">Informations de connexion</legend>
            <label for="mail" class="visually-hidden">Adresse e-mail</label>
            <input type="email" id="mail" name="mail" placeholder="Email" required aria-required="true">

            <label for="mdp" class="visually-hidden">Mot de passe</label>
            <input type="password" id="mdp" name="mdp" placeholder="Mot de passe" required aria-required="true">

        <button type="submit" class="btn-login">Se connecter</button>
    </form>
    <a href="create-account" class="btn-blue">Pas encore de compte ?</a>
</section>

<?php
$content = ob_get_clean();
require SRC_DIR . '/view/layout.php';
?>