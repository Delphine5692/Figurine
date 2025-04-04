<?php

namespace Figurine\Model;

use Figurine\Lib\DbConnector;

class Utilisateur
{
    private $db;

    public function __construct()
    {
        $this->db = DbConnector::dbConnect();
    }

    public function verifierConnexion($mail, $mdp)
    {
        $sql = "SELECT * FROM UTILISATEUR WHERE mail = ?";
        $stmt = $this->db->prepare($sql); 
        $stmt->execute([$mail]);
        $utilisateur = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($utilisateur && $utilisateur['mdp'] === $mdp) {
            return $utilisateur;
        }

        return false;
    }
}
