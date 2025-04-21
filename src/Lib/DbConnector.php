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
            return self::$bdd; // retourne le connecteur singleton PDO si déjà créé
        } else {
            try {
                // sinon, création de la connexion PDO (et stockage dans la variable de classe $bdd)
                self::$bdd = new PDO(
                    "mysql:dbname=" . $_ENV['DB_NAME'] . "; host=" . $_ENV['DB_HOST'] . "; charset=utf8",
                    $_ENV['DB_LOGIN'],
                    $_ENV['DB_PASSWORD']
                );
                self::$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return self::$bdd;
            } catch (PDOException $e) {
                throw new Exception($e->getMessage());
            }
        }
    }
}
