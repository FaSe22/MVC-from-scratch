<?php

namespace app\src\repositories;

use app\src\models\Model;
use app\src\services\DatabaseConnector;

class BaseRepository
{


    /**
     * @param Model $model
     * @param $args
     * @return mixed Id des erstellten Models
     */
    public static function create(Model $model, $args)
    {
        $PDO = DatabaseConnector::getPDO();
        $prepared = static::prepareInsertStatement($model, $PDO, $args);
        $PDO->query($prepared->queryString);
        // hier wird die Id des zuletzt erstellen Models zurück gegeben.
        // damit wir diese in die instanz des models schreiben können.
        // damit bin ich nicht ganz zufrieden.
        return static::getLatestEntry($model)['id'];
    }

    public static function getLatestEntry(Model $model)
    {
        $table_name = $model::getTableName();
        $PDO = DatabaseConnector::getPDO();
        $prepared = $PDO->prepare("SELECT MAX(id) as id from $table_name");
        return $PDO->query($prepared->queryString)->fetch();
    }

    public static function get(string $table_name): bool|array
    {
        $PDO = DatabaseConnector::getPDO();
        $prepared = $PDO->prepare("SELECT * from $table_name");
        return $PDO->query($prepared->queryString)->fetchAll();
    }

    public static function find(string $table_name, int $id): bool|array
    {
        $PDO = DatabaseConnector::getPDO();
        $prepared = $PDO->prepare("SELECT * from $table_name WHERE id = $id");
        return $PDO->query($prepared->queryString)->fetch();
    }

    public static function delete(Model $model)
    {
        $table_name = $model::getTableName();
        $PDO = DatabaseConnector::getPDO();
        $prepared = $PDO->prepare("DELETE FROM $table_name WHERE id = $model->id");
        return $PDO->query($prepared->queryString);
    }


    public function truncate(string $table): bool|\PDOStatement
    {
        $PDO = DatabaseConnector::getPDO();
        $prepared = $PDO->prepare("TRUNCATE $table");
        return $PDO->query($prepared->queryString);
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
     * @param \PDO $PDO
     * @param array|null $args
     * @return false|\PDOStatement
     */
    public static function prepareInsertStatement(Model $model, \PDO $PDO, ?array $args): \PDOStatement|false
    {
        $table_name = $model::getTableName();
        list($keys, $values) = static::getInputData($args);
        return $PDO->prepare("INSERT INTO $table_name ($keys) VALUES ('$values')");
    }


}
