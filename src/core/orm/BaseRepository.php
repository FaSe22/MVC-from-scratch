<?php

namespace app\src\core\orm;

use app\src\core\Model;
use PDO;
use PDOStatement;

class BaseRepository
{

    /**
     * @param Model $model
     * @param $args
     * @return mixed Id des erstellten Models
     */
    public static function create(Model $model, $args): mixed
    {
        $pdo = DatabaseConnector::getPDO();
        $prepared = static::prepareInsertStatement($model, $pdo, $args);
        $pdo->query($prepared->queryString);
        // hier wird die Id des zuletzt erstellen Models zurückgegeben.
        // damit wir diese in die instanz des models schreiben können.
        // damit bin ich nicht ganz zufrieden.
        return static::getLatestEntry($model)['id'];
    }

    public static function getLatestEntry(Model $model)
    {
        $tableName = $model::getTableName();
        $pdo = DatabaseConnector::getPDO();
        $prepared = $pdo->prepare("SELECT MAX(id) as id from $tableName");
        return $pdo->query($prepared->queryString)->fetch();
    }

    public static function get(string $tableName): bool|array
    {
        $pdo = DatabaseConnector::getPDO();
        $prepared = $pdo->prepare("SELECT * from $tableName");
        return $pdo->query($prepared->queryString)->fetchAll();
    }

    public static function find(string $tableName, int $id): bool|array
    {
        $pdo = DatabaseConnector::getPDO();
        $prepared = $pdo->prepare("SELECT * from $tableName WHERE id = $id");
        return $pdo->query($prepared->queryString)->fetch();
    }

    public static function delete(Model $model): bool|PDOStatement
    {
        $tableName = $model::getTableName();
        $pdo = DatabaseConnector::getPDO();
        $prepared = $pdo->prepare("DELETE FROM $tableName WHERE id = $model->id");
        return $pdo->query($prepared->queryString);
    }


    public function truncate(string $table): bool|PDOStatement
    {
        $pdo = DatabaseConnector::getPDO();
        $prepared = $pdo->prepare("TRUNCATE $table");
        return $pdo->query($prepared->queryString);
    }


    /**
     * @param array $arr
     * @return array
     */
    public static function getInputData(array $arr): array
    {
        $keys = implode(",", array_keys($arr));
        $values = implode("','", array_values($arr));
        return array($keys, $values);
    }

    /**
     * @param Model $model
     * @param PDO $pdo
     * @param array|null $args
     * @return false|PDOStatement
     */
    public static function prepareInsertStatement(Model $model, PDO $pdo, ?array $args): PDOStatement|false
    {
        $tableName = $model::getTableName();
        list($keys, $values) = static::getInputData($args);
        return $pdo->prepare("INSERT INTO $tableName ($keys) VALUES ('$values')");
    }


}
