<?php
$title = "Inscription - Figurine";
$description = "Créez votre compte pour accéder à l'univers de Figurine et découvrir nos produits artisanaux.";
ob_start();
?>

    <section class="account-container">
        <header>
            <h1>Inscription</h1>
        </header>

        <form class="account-form" method="POST" action="create-account" aria-label="Formulaire d'inscription">
            <label for="nom" class="visually-hidden">Nom</label>
            <input type="text" name="nom" id="nom" placeholder="Nom" required>

            <label for="prenom" class="visually-hidden">Prénom</label>
            <input type="text" name="prenom" id="prenom" placeholder="Prénom" required>

            <label for="mail" class="visually-hidden">Email</label>
            <input type="email" name="mail" id="mail" placeholder="Email" required>

            <label for="mdp" class="visually-hidden">Mot de passe</label>
            <input type="password" name="mdp" id="mdp" placeholder="Mot de passe" required>

            <label for="mdp_confirm" class="visually-hidden">Confirmer le mot de passe</label>
            <input type="password" name="mdp_confirm" id="mdp_confirm" placeholder="Confirmer le mot de passe" required>

            <button type="submit" class="btn-submit">S'inscrire</button>
        </form>
        <p>Déjà inscrit ? <a href="login" class="btn-blue">Connectez-vous</a></p>
    </section>

<?php
$content = ob_get_clean();
require SRC_DIR . '/view/layout.php';
?>