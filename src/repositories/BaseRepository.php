<?php

namespace app\src\repositories;

use app\src\models\Model;
use app\src\services\DatabaseConnector;

class BaseRepository
{

    private DatabaseConnector $connector;

    public static function connect()
    {
        $instance = null;

        if (!$instance) {
            $instance = new self(new DatabaseConnector());
        }
        return $instance;

    }

    public function __construct($connector)
    {
        $this->connector = $connector;
    }


    public function create(Model $model, $args)
    {
        $PDO = $this->connector->getPDO();
        $prepared = $this->prepareInsertStatement($model, $PDO, $args);
        $PDO->query($prepared->queryString);
        return $this->getLatestEntry($model)['id'];
    }

    public function getLatestEntry(Model $model)
    {
       $table_name = $model::getTableName();
        $PDO = $this->connector->getPDO();
        $prepared = $PDO->prepare("SELECT MAX(id) as id from $table_name");
       return  $PDO->query($prepared->queryString)->fetch();
    }

    public function get(string $table_name): bool|array
    {
        $PDO = $this->connector->getPDO();
        $prepared = $PDO->prepare("SELECT * from $table_name");
        return $PDO->query($prepared->queryString)->fetchAll();
    }

    public function find(string $table_name, int $id): bool|array
    {
        $PDO = $this->connector->getPDO();
        $prepared = $PDO->prepare("SELECT * from $table_name WHERE id = $id");
        return $PDO->query($prepared->queryString)->fetchAll();
    }

    public function delete(Model $model)
    {
        $table_name = $model::getTableName();
        $PDO = $this->connector->getPDO();
        $prepared = $PDO->prepare("DELETE FROM $table_name WHERE id = $model->id");
        return $PDO->query($prepared->queryString);
    }


    public function truncate(string $table): bool|\PDOStatement
    {
        $PDO = $this->connector->getPDO();
        $prepared = $PDO->prepare("TRUNCATE $table");
        return $PDO->query($prepared->queryString);
    }


    /**
     * @param array $arr
     * @return array
     */
    public function getInputData(array $arr): array
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
    public function prepareInsertStatement(Model $model, \PDO $PDO, ?array $args): \PDOStatement|false
    {
        $table_name = $model::getTableName();
        list($keys, $values) = $this->getInputData($args);
        return $PDO->prepare("INSERT INTO $table_name ($keys) VALUES ('$values')");
    }


}
