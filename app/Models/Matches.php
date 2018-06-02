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
        $sql = "SELECT match_type, match_type_id, match_start, team_1, team_1_id, team_2, team_2_id
                FROM view_matches
                ORDER BY match_start";
        $db = static::getDB(Config::get('DB_NAME'));
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $matches = $stmt->fetchAll(PDO::FETCH_GROUP);

        return $matches;
    }
}
