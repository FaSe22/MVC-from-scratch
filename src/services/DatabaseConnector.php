<?php

namespace app\src\services;

class DatabaseConnector
{
    public static function getPDO(): \PDO
    {
        $conf = include('./config/database.php');

        $sql = $conf['sql'];

        $dsn = "mysql:host=" . $sql['host'] .
            ";dbname=" . $sql['db'] .
            ";charset=" . $sql['charset'] .
            ";port=" . $sql['port'];

        return new \PDO($dsn, "root", "");
    }
}
