<?php
session_start();

// Si l'utilisateur est déjà connecté, redirige-le vers la page d'accueil
if (isset($_SESSION['id_utilisateur'])) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <h1>Connexion</h1>
    <!-- Formulaire de connexion -->
    <form action="/web/Figurine/index.php?url=login" method="POST">
    <input type="email" name="mail" placeholder="Email" required>
    <input type="password" name="mdp" placeholder="Mot de passe" required>
    <button type="submit">Se connecter</button>
</form>

<a href="index.php?url=inscription">Pas encore de compte ?</a>
</body>
</html>
