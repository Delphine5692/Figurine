<?php

namespace Figurine\Lib;

use PDO;
use PDOException;
use Exception;

abstract class DbConnector
{
    private static $bdd = null;

    public static function dbConnect()
    {
        if (isset(self::$bdd)) {
            return self::$bdd;
        } else {
            try {
                // Création de la connexion PDO
                self::$bdd = new PDO(
                    "mysql:dbname=" . \DB_NAME . "; host=" . DB_HOST . "; charset=utf8",
                    DB_USER,
                    DB_PASSWORD
                );
                self::$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return self::$bdd;
            } catch (PDOException $e) {
                throw new Exception($e->getMessage());
            }
        }
    }
}
