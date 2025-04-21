<?php

namespace Figurine\Model;

use Figurine\Lib\DbConnector;
use PDO;
use PDOException;

abstract class BaseModel
{
    /**
     * Retourne l'instance PDO utilisée pour communiquer avec la base de données.
     *
     * @return PDO
     */
    protected static function getDb(): PDO
    {
        return DbConnector::dbConnect();
    }

    /**
     * Méthode générique pour exécuter une requête préparée.
     *
     * @param string $query La requête SQL.
     * @param array $params Les paramètres de la requête.
     * @return array|bool Tableau associatif en cas de SELECT, true/false sinon.
     */
    protected static function executeQuery(string $query, array $params = [])
    {
        try {
            $stmt = self::getDb()->prepare($query);
            $stmt->execute($params);
            // Si la requête est de type SELECT on retourne les résultats
            if (stripos(trim($query), 'SELECT') === 0) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            return true;
        } catch (PDOException $e) {
            error_log('Erreur SQL : ' . $e->getMessage());
            return false;
        }
    }
}
