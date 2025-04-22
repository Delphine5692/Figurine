<?php

namespace Figurine\Lib;

use PDO;
use PDOException;
use Exception;

/**
 * Classe abstraite DbConnector
 *
 * Fournit une méthode statique pour obtenir une instance unique (singleton) de la connexion PDO.
 * Si la connexion est déjà établie, elle est retournée directement.
 * Sinon, une nouvelle connexion est créée en utilisant les variables d'environnement contenant
 * les informations de connexion à la base de données (nom de la base, hôte, login, mot de passe).
 */
abstract class DbConnector
{
    /**
     * @var PDO|null $bdd Stocke l'instance unique de connexion PDO.
     */
    private static $bdd = null;

    /**
     * Connexion à la base de données.
     *
     * Si une connexion est déjà établie (singleton), elle est renvoyée.
     * Sinon, une nouvelle connexion PDO est créée, configurée pour lancer des exceptions en cas d'erreur,
     * et stockée dans la propriété statique $bdd.
     *
     * @return PDO L'instance de connexion PDO.
     * @throws Exception En cas d'échec de la connexion à la base de données.
     */
    public static function dbConnect()
    {
        // Si la connexion existe déjà, retourne le connecteur PDO existant.
        if (isset(self::$bdd)) {
            return self::$bdd;
        } else {
            try {
                // Création de la connexion PDO avec les paramètres définis dans les variables d'environnement.
                self::$bdd = new PDO(
                    "mysql:dbname=" . $_ENV['DB_NAME'] . "; host=" . $_ENV['DB_HOST'] . "; charset=utf8",
                    $_ENV['DB_LOGIN'],
                    $_ENV['DB_PASSWORD']
                );
                // Configure PDO pour lancer des exceptions en cas d'erreur.
                self::$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // Retourne la connexion nouvellement créée.
                return self::$bdd;
            } catch (PDOException $e) {
                // En cas d'erreur lors de la connexion, lance une exception avec le message d'erreur.
                throw new Exception($e->getMessage());
            }
        }
    }
}