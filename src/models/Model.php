<?php

namespace app\src\models;

use app\src\repositories\BaseRepository;
use app\src\services\PropertyMapper;

abstract class Model
{

    public ?int $id;

    /**
     * @param $args
     * @return mixed
     */
    public static function create($args): Model
    {
        $instance = static::getInstance($args, "mapColumnsToProperties");
        //Wir nehmen die neue Id und schreiben sie in die Instanz
        $instance->id = BaseRepository::create($instance, $args);

        return $instance;
    }


    /**
     * @param bool|array $entry
     * @param $fn
     * @param null $instance
     * @return Model
     */
    public static function getInstance(bool|array &$entry, $fn, $instance = null): Model
    {
        if (!$instance) {
            $instance = new (get_called_class());
        }

        $properties = get_class_vars(get_class($instance));
        PropertyMapper::$fn($properties, $entry, $instance);

        return $instance;

    }

    /**
     * @return $this
     */
    public function save(): Model
    {
        $args = [];
        $instance = static::getInstance($args, "mapPropertiesToColumns", $this);
        $instance->id = BaseRepository::create($this, $args);

        return $instance;
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        BaseRepository::delete($this);
        return true;

    }


    /**
     * @return array
     */
    public static function get(): array
    {
        $entries = BaseRepository::get(static::getTableName());
        static::format($entries);

        return array_map(fn($entry) =>  static::getInstance($entry, "mapColumnsToProperties"), $entries);
    }

    /**
     * @param bool|array $entries
     * @return void
     */
    public static function format(bool|array $entries): void
    {
        // die daten kommen so ["id" => 1 [0] => 1 "title" => "test" [1] => "test"],
        // entferne alle numerischen keys aus den ergebnissen
        // und wir erhalten ["id" => 1, "title" => "test"]
        array_map(function ($key) use ($entries) {
            if (is_numeric($key)) {
                unset($entries[0][$key]);
            }
        }, array_keys($entries[0]));
    }


    /**
     * @return string
     */
    public static function getTableName(): string
    {
        $string = str_replace('\\', '/', get_called_class());
        return (strtolower(basename($string)) . "s");
    }

    /**
     * @param int $id
     * @return Model|null
     */
    public static function find(int $id): ?Model
    {
        $entry = BaseRepository::find(static::getTableName(), $id);

        if (!empty($entry)) {
            return static::getInstance($entry, "mapColumnsToProperties");
        } else {
            return null;
        }
    }
}