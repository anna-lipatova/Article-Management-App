<?php

class Database
{
    private static $connection = null;

    public static function getConnection()
    {
        require_once __DIR__ . '/../db_config.php';

        if (self::$connection === null) {
            self::$connection = new mysqli($db_config['server'], $db_config['login'], $db_config['password'], $db_config['database']);

            if (self::$connection->connect_errno) {
                die('mysqli connection error: ' . $mysqli->connect_error);
            }
        }

        return self::$connection;
    }
}