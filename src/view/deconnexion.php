<?php
session_start();
session_destroy(); // Détruire la session

// Rediriger vers la page de connexion ou l'accueil
header('Location: /web/Figurine/index.php');
exit;
