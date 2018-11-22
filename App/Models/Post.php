<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;
use PDO;

class Post extends Model
{

    /**
     * @return array
     */
    public static function getAll()
    {
//        $host = 'localhost';
//        $db_name = 'mvc';
//        $username = 'root';
//        $password = 'root';

        try{
//            $db = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
            $db = static::getDB();

            $stmt = $db->query('SELECT id, title, content FROM posts ORDER BY created_at');
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
        }catch (PDOException $e){
            echo $e->getMessage();
        }
    }

}