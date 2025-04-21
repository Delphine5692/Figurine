<?php

namespace Figurine\Model;

use Figurine\Lib\DbConnector;
use PDO;
use PDOException;

class User
{
    private $db;

    public function __construct()
    {
        $this->db = DbConnector::dbConnect();
    }

    /**
     * Vérifie les informations de connexion d'un utilisateur.
     * @param string $mail L'adresse email de l'utilisateur.
     * @param string $mdp Le mot de passe non haché de l'utilisateur.
     * @return array|bool Retourne les informations de l'utilisateur si la connexion est réussie, sinon false.
     */
    public function checkLogin($mail, $mdp)
    {
        try {
            $sql = "SELECT id_utilisateur, nom, prenom, role, mdp, statut FROM utilisateur WHERE mail = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$mail]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($mdp, $user['mdp'])) {
                return $user;
            }

            return false;
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans checkLogin : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Crée un nouvel utilisateur dans la base de données.
     * @param string $nom Le nom de l'utilisateur.
     * @param string $prenom Le prénom de l'utilisateur.
     * @param string $mail L'adresse email de l'utilisateur.
     * @param string $mdp_hache Le mot de passe haché de l'utilisateur.
     * @return bool Retourne true si l'utilisateur a été créé avec succès, sinon false.
     */
    public function createUser($nom, $prenom, $mail, $mdp_hache)
    {
        try {
            // Vérifier si l'email existe déjà
            $stmt = $this->db->prepare("SELECT * FROM utilisateur WHERE mail = ?");
            $stmt->execute([$mail]);
            $user = $stmt->fetch();

            if ($user) {
                return false; // L'email existe déjà
            }

            // Insérer le nouvel utilisateur
            $stmt = $this->db->prepare("INSERT INTO utilisateur (nom, prenom, mail, mdp) VALUES (?, ?, ?, ?)");

            $stmt->execute([
                ($nom),
                ($prenom),
                ($mail),
                $mdp_hache
            ]);

            return true; // Inscription réussie
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans createUser : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime un utilisateur.
     * @param int $id_user L'identifiant unique de l'utilisateur.
     * @return bool Retourne true si l'utilisateur a été supprimé avec succès, sinon false.
     */
    public function deleteUser($id_user)
    {
        if (!is_numeric($id_user)) {
            throw new \InvalidArgumentException('ID utilisateur invalide pour suppression');
        }

        try {
            // Marquer l'utilisateur comme "supprime"
            $stmt = $this->db->prepare("UPDATE utilisateur SET statut = 'supprime' WHERE id_utilisateur = ?");
            $stmt->execute([$id_user]);

            // Supprime tous les commentaires associés à l'utilisateur
            $stmt = $this->db->prepare("DELETE FROM commentaire WHERE id_utilisateur = ?");
            $stmt->execute([$id_user]);

            // Supprimer l'utilisateur de la base de données
            $stmt = $this->db->prepare("DELETE FROM utilisateur WHERE id_utilisateur = ?");
            $result = $stmt->execute([$id_user]);

            if (!$result) {
                error_log('deleteUser retourne false pour id: ' . $id_user);
            }

            return $result;
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans deleteUser : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Met à jour l'adresse d'un utilisateur.
     * @param int $id_user L'identifiant unique de l'utilisateur.
     * @param string $adress La nouvelle adresse de l'utilisateur.
     * @return bool Retourne true si l'adresse a été mise à jour avec succès, sinon false.
     */
    public function updateAddress($id_user, $adress)
    {
        if (!is_numeric($id_user) || empty($adress)) {
            throw new \InvalidArgumentException('Données invalides pour mettre à jour l\'adresse');
        }

        try {
            $stmt = $this->db->prepare("UPDATE utilisateur SET adresse = ? WHERE id_utilisateur = ?");
            $stmt->execute([$adress, $id_user]);
            return true;
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans updateAddress : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère les informations d'un utilisateur par son ID.
     * @param int $id_user L'identifiant unique de l'utilisateur.
     * @return array|null Retourne un tableau contenant les informations de l'utilisateur ou null en cas d'erreur.
     */
    public function getUserById($id_user)
    {
        if (!is_numeric($id_user)) {
            throw new \InvalidArgumentException('ID utilisateur invalide');
        }

        try {
            $stmt = $this->db->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = ?");
            $stmt->execute([$id_user]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans getUserById : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupère le rôle d'un utilisateur.
     * @param int $id_user L'identifiant unique de l'utilisateur.
     * @return string|null Retourne le rôle de l'utilisateur ou null en cas d'erreur.
     */
    public function getRole($id_user)
    {
        if (!is_numeric($id_user)) {
            throw new \InvalidArgumentException('ID utilisateur invalide');
        }

        try {
            $stmt = $this->db->prepare("SELECT role FROM utilisateur WHERE id_utilisateur = ?");
            $stmt->execute([$id_user]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans getRole : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Modifie le rôle d'un utilisateur.
     * @param int $id_user L'identifiant unique de l'utilisateur.
     * @param string $role Le nouveau rôle à attribuer à l'utilisateur.
     * @return bool Retourne true si le rôle a été modifié avec succès, sinon false.
     */
    public function modifierRole($id_user, $role)
    {
        $validRoles = ['admin', 'user'];
        if (!in_array($role, $validRoles)) {
            throw new \InvalidArgumentException('Rôle invalide');
        }

        if (!is_numeric($id_user)) {
            throw new \InvalidArgumentException('ID utilisateur invalide');
        }

        try {
            $stmt = $this->db->prepare("UPDATE utilisateur SET role = ? WHERE id_utilisateur = ?");
            $stmt->execute([$role, $id_user]);
            return true;
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans modifierRole : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère tous les utilisateurs.
     * @return array|null Retourne un tableau contenant tous les utilisateurs ou null en cas d'erreur.
     */
    public function getAllUsers()
    {
        try {
            $stmt = $this->db->query("SELECT id_utilisateur, nom, mail, role, statut FROM utilisateur");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Parcourir chaque utilisateur et assigner des valeurs par défaut
            foreach ($users as &$user) {
                if (!isset($user['statut'])) {
                    $user['statut'] = 'actif';
                }
            }

            return $users;
        } catch (PDOException $e) {
            error_log('Erreur dans getAllUsers : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Met à jour le statut d'un utilisateur.
     * @param int $userId L'identifiant unique de l'utilisateur.
     * @param string $statut Le nouveau statut à appliquer (actif ou supprimer).
     * @return bool Retourne true si le statut a été mis à jour avec succès, sinon false.
     */
    public function updateStatus($userId, $statut)
    {
        if (!is_numeric($userId)) {
            throw new \InvalidArgumentException('ID utilisateur invalide');
        }

        $validStatuses = ['actif', 'supprimer'];
        if (!in_array($statut, $validStatuses)) {
            throw new \InvalidArgumentException('Statut invalide');
        }

        try {
            $pdo = DbConnector::dbConnect();
            $stmt = $pdo->prepare("UPDATE utilisateur SET statut = :statut WHERE id_utilisateur = :id");
            return $stmt->execute([
                'statut' => $statut,
                'id' => $userId
            ]);
        } catch (PDOException $e) {
            error_log('Erreur dans updateStatus : ' . $e->getMessage());
            return false;
        }
    }



}
