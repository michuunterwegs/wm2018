<?php

namespace Models;

use PDO;
use \Utilities\Token;
use \Core\Config;

/**
 * Matches modell
 *
 * PHP version 7.0
 */
class Matches extends \Core\Model
{
    /**
     * Get all matches
     * 
     * @return array Queried comics metadata
     */
    public static function getAll()
    {
        $sql = "SELECT *
                FROM view_matches
                ORDER BY match_start";
        $db = static::getDB(Config::get('DB_NAME'));
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $matches = $stmt->fetchAll(PDO::FETCH_CLASS, get_called_class());

        return $matches;
    }
}
