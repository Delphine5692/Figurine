<?php

namespace Figurine\Controller;

use Figurine\Lib\DbConnector; // On inclut la classe DbConnector

class UtilisateurController
{
    public function connexion()
{
    if (!empty($_POST['mail']) && !empty($_POST['mdp'])) {
        $mail = $_POST['mail'];
        $mdp = $_POST['mdp'];

        $utilisateurModel = new \Figurine\Model\Utilisateur();

        $utilisateur = $utilisateurModel->verifierConnexion($mail, $mdp);

        if ($utilisateur) {
            // Connexion réussie
            session_start();
            $_SESSION['id_utilisateur'] = $utilisateur['id_utilisateur'];
            $_SESSION['nom'] = $utilisateur['nom'];

            // Rediriger vers la page d'accueil
            header('Location: /web/Figurine/index.php');
            exit;
        } else {
            echo "Identifiants incorrects.";
        }
    } else {
        echo "Veuillez remplir tous les champs.";
    }
}


    public function inscription()
    {

        var_dump($_POST); // Afficher ce qui est envoyé dans le formulaire

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $mail = $_POST['mail'];
            $mdp = $_POST['mdp'];
            $mdp_confirm = $_POST['mdp_confirm'];

            // Vérifie que les mots de passe correspondent
            if ($mdp !== $mdp_confirm) {
                echo "Les mots de passe ne correspondent pas.";
                return;
            }

            $mdp_hache = password_hash($mdp, PASSWORD_DEFAULT);

            try {
                // Utiliser DbConnector pour obtenir la connexion
                $pdo = DbConnector::dbConnect();

                // Vérifier si l'email existe déjà
                $stmt = $pdo->prepare("SELECT * FROM UTILISATEUR WHERE mail = ?");
                $stmt->execute([$mail]);
                $utilisateur = $stmt->fetch();

                if ($utilisateur) {
                    echo "Cet email est déjà utilisé.";
                } else {
                    // Insérer le nouvel utilisateur
                    $stmt = $pdo->prepare("INSERT INTO UTILISATEUR (nom, prenom, mail, mdp) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$nom, $prenom, $mail, $mdp_hache]);

                    echo "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                    header('Location: index.php?url=connexion');
                    exit;
                }
            } catch (\PDOException $e) {
                echo "Erreur de base de données : " . $e->getMessage();
            }
        } else {
            require 'C:\Users\kerx31\Documents\monPHP\Figurine\src\view\inscription.php';
        }
    }
}
