<?php

namespace Figurine\Model;

use Figurine\Lib\DbConnector;
use PDO;
use PDOException;

class User
{
    // Propriété pour stocker l'instance PDO utilisée pour les opérations sur la base.
    private $db;

    /**
     * Constructeur
     *
     * Initialise la connexion à la base de données en utilisant la classe DbConnector.
     */
    public function __construct()
    {
        $this->db = DbConnector::dbConnect();
    }

    /**
     * Vérifie les informations de connexion d'un utilisateur.
     *
     * Exécute une requête préparée afin de récupérer les informations associées à l'email fourni.
     * Vérifie ensuite le mot de passe en utilisant password_verify().
     *
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
     *
     * Avant l'insertion, vérifie si l'adresse email est déjà utilisée.
     *
     * @param string $nom Le nom de l'utilisateur.
     * @param string $prenom Le prénom de l'utilisateur.
     * @param string $mail L'adresse email de l'utilisateur.
     * @param string $mdp_hache Le mot de passe haché de l'utilisateur.
     * @return bool Retourne true si l'utilisateur a été créé avec succès, sinon false.
     */
    public function createUser($nom, $prenom, $mail, $mdp_hache)
    {
        try {
            // Vérifie si un utilisateur avec cet email existe déjà.
            $stmt = $this->db->prepare("SELECT * FROM utilisateur WHERE mail = ?");
            $stmt->execute([$mail]);
            $user = $stmt->fetch();

            if ($user) {
                return false; // L'email est déjà utilisée.
            }

            // Insère le nouvel utilisateur dans la base de données.
            $stmt = $this->db->prepare("INSERT INTO utilisateur (nom, prenom, mail, mdp) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $nom,
                $prenom,
                $mail,
                $mdp_hache
            ]);

            return true; // Inscription réussie.
        } catch (PDOException $e) {
            error_log('Erreur de base de données dans createUser : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime un utilisateur.
     *
     * Cette méthode se charge de marquer l'utilisateur comme supprimé,
     * puis de supprimer ses commentaires et enfin de le supprimer de la base.
     *
     * @param int $id_user L'identifiant unique de l'utilisateur.
     * @return bool Retourne true si l'utilisateur est supprimé avec succès, sinon false.
     * @throws \InvalidArgumentException Si l'ID utilisateur n'est pas un nombre.
     */
    public function deleteUser($id_user)
    {
        if (!is_numeric($id_user)) {
            throw new \InvalidArgumentException('ID utilisateur invalide pour suppression');
        }

        try {
            // Marquer l'utilisateur comme "supprime".
            $stmt = $this->db->prepare("UPDATE utilisateur SET statut = 'supprime' WHERE id_utilisateur = ?");
            $stmt->execute([$id_user]);

            // Supprime tous les commentaires associés à cet utilisateur.
            $stmt = $this->db->prepare("DELETE FROM commentaire WHERE id_utilisateur = ?");
            $stmt->execute([$id_user]);

            // Supprime l'utilisateur de la base.
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
     *
     * Vérifie que l'ID utilisateur est numérique et que l'adresse n'est pas vide,
     * puis met à jour l'adresse dans la base.
     *
     * @param int $id_user L'identifiant unique de l'utilisateur.
     * @param string $adress La nouvelle adresse de l'utilisateur.
     * @return bool Retourne true si l'adresse a été mise à jour, sinon false.
     * @throws \InvalidArgumentException Si les données d'entrée sont invalides.
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
     *
     * Exécute une requête préparée pour retourner l'utilisateur correspondant.
     *
     * @param int $id_user L'identifiant unique de l'utilisateur.
     * @return array|null Retourne un tableau avec les informations de l'utilisateur ou null si une erreur se produit.
     * @throws \InvalidArgumentException Si l'ID utilisateur est invalide.
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
     *
     * Exécute une requête pour obtenir la colonne "role" de l'utilisateur correspondant à l'ID.
     *
     * @param int $id_user L'identifiant unique de l'utilisateur.
     * @return string|null Retourne le rôle de l'utilisateur ou null en cas d'erreur.
     * @throws \InvalidArgumentException Si l'ID utilisateur est invalide.
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
     *
     * Vérifie que le nouveau rôle fait partie des rôles autorisés (admin, user),
     * puis met à jour la table utilisateur.
     *
     * @param int $id_user L'identifiant unique de l'utilisateur.
     * @param string $role Le nouveau rôle à attribuer à l'utilisateur.
     * @return bool Retourne true si le rôle a été modifié, sinon false.
     * @throws \InvalidArgumentException Si l'ID utilisateur ou le rôle est invalide.
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
     *
     * Exécute une requête SELECT pour obtenir les informations essentielles de chaque utilisateur.
     * Assure qu'un statut est défini pour chaque utilisateur (valeur par défaut 'actif' si absent).
     *
     * @return array|null Retourne un tableau associatif contenant tous les utilisateurs ou null en cas d'erreur.
     */
    public function getAllUsers()
    {
        try {
            $stmt = $this->db->query("SELECT id_utilisateur, nom, mail, role, statut FROM utilisateur");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Parcourt chaque utilisateur afin d'assigner 'actif' comme statut par défaut si non défini.
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
     *
     * Vérifie que l'ID utilisateur est numérique et que le statut fourni est l'un des statuts autorisés ('actif', 'supprimer').
     * Puis met à jour le statut dans la base.
     *
     * @param int $userId L'identifiant unique de l'utilisateur.
     * @param string $statut Le nouveau statut à appliquer (actif ou supprimer).
     * @return bool Retourne true si la mise à jour est réalisée avec succès, sinon false.
     * @throws \InvalidArgumentException Si l'ID utilisateur ou le statut est invalide.
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
?>