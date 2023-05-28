<?php

namespace app\src\models;

use app\src\repositories\BaseRepository;

abstract class Model
{

    public function save(): static
    {
        $properties = get_class_vars(get_class($this));
        $args = [];
        foreach (array_keys($properties) as $property) {
            if ($property == "id") continue;
            $args[$property] = $this->$property;
        }
        $id = BaseRepository::connect()->create($this, $args);
        $this->id = $id;
        return $this;
    }

    public function delete(): void
    {
        BaseRepository::connect()->delete($this);
    }

    public static function create($args)
    {
        $instance = (new (get_called_class()));
        $properties = get_class_vars(get_class($instance));
        foreach (array_keys($properties) as $property) {
            if ($property == "id") continue;
            $instance->$property = $args[$property];
        }

        $id = (BaseRepository::connect()->create($instance, $args));
        $instance->id = $id;
        return $instance;
    }

    public static function get()
    {
        $entries = BaseRepository::connect()->get(static::getTableName());
        array_map(function ($array_key) use ($entries) {
            if (is_numeric($array_key)) unset($entries[0][$array_key]);
        }, array_keys($entries[0]));

        $instances = [];

        foreach ($entries as $model) {
            $instance = (new (get_called_class()));
            $props = get_class_vars(get_class($instance));
            foreach (array_keys($props) as $key) {
                $instance->$key = $model[$key];
            }

            $instances[] = $instance;
        }

        return $instances;


    }

    public static function find(int $id)
    {
        $entry = BaseRepository::connect()->find(static::getTableName(), $id);

        $instance = (new (get_called_class()));
        $props = get_class_vars(get_class($instance));
        foreach (array_keys($props) as $key) {
            $instance->$key = $entry[0][$key];
        }

        return $instance;

    }

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        $string = str_replace('\\', '/', get_called_class());
        return (strtolower(basename($string)) . "s");
    }


}